<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class EmailValidationService
{
    private $client;
    private $config;
    
    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'verify' => false, // Desativar SSL verify se tiver problemas
        ]);
        
        $this->config = [
            'hunter_api_key' => env('HUNTER_API_KEY'),
            'abstract_api_key' => env('ABSTRACT_EMAIL_API_KEY'),
            'mailboxlayer_key' => env('MAILBOXLAYER_API_KEY'),
        ];
    }
    
    /**
     * Valida um email usando múltiplos serviços
     */
    public function validateEmail(string $email): array
    {
        $cacheKey = 'email_validation_' . md5($email);
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        
        $result = [
            'valid' => false,
            'disposable' => false,
            'free' => false,
            'deliverable' => false,
            'smtp_check' => false,
            'score' => 0,
            'sources' => [],
            'provider' => null,
        ];
        
        // Tentar múltiplos serviços em ordem
        $services = ['hunter', 'emailrep', 'abstract', 'mailboxlayer'];
        
        foreach ($services as $service) {
            try {
                $serviceResult = $this->validateWithService($service, $email);
                
                if ($serviceResult !== null) {
                    $result = array_merge($result, $serviceResult);
                    $result['provider'] = $service;
                    break;
                }
            } catch (\Exception $e) {
                Log::warning("Falha no serviço {$service} para email {$email}: " . $e->getMessage());
                continue;
            }
        }
        
        // Se nenhum serviço funcionou, fazer validação básica
        if (!$result['provider']) {
            $result = $this->basicValidation($email);
        }
        
        // Cache por 24 horas
        Cache::put($cacheKey, $result, now()->addHours(24));
        
        return $result;
    }
    
    /**
     * Método correto que chama o serviço específico
     */
    private function validateWithService(string $service, string $email): ?array
    {
        return match($service) {
            'hunter' => $this->validateWithHunter($email),
            default => null,
        };
    }
    
    /**
     * Valida com Hunter.io (50 verificações gratuitas/mês)
     */
    private function validateWithHunter(string $email): ?array
    {
        if (empty($this->config['hunter_api_key'])) {
            return null;
        }
        
        $response = $this->client->get('https://api.hunter.io/v2/email-verifier', [
            'query' => [
                'email' => $email,
                'api_key' => $this->config['hunter_api_key'],
            ]
        ]);
        
        $data = json_decode($response->getBody(), true);
        
        if (isset($data['data'])) {
            return [
                'valid' => $data['data']['status'] === 'valid',
                'disposable' => $data['data']['disposable'] ?? false,
                'free' => $data['data']['webmail'] ?? false,
                'deliverable' => $data['data']['result'] === 'deliverable',
                'smtp_check' => $data['data']['smtp_check'] ?? false,
                'score' => $data['data']['score'] ?? 0,
                'sources' => $data['data']['sources'] ?? [],
            ];
        }
        
        return null;
    }
    
    /**
     * Valida com EmailRep.io (gratuito, sem chave)
     */
    private function validateWithEmailRep(string $email): ?array
    {
        try {
            $response = $this->client->get('https://emailrep.io/' . urlencode($email), [
                'headers' => [
                    'User-Agent' => 'PortalHSE/1.0',
                    'Key' => 'public', // EmailRep.io requer este header para uso público
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if (isset($data['email'])) {
                return [
                    'valid' => !($data['details']['invalid_format'] ?? false),
                    'disposable' => $data['details']['disposable'] ?? false,
                    'free' => $data['details']['free_provider'] ?? false,
                    'deliverable' => true, // EmailRep não tem este campo
                    'smtp_check' => $data['details']['smtp_valid'] ?? false,
                    'score' => $this->calculateScoreFromEmailRep($data),
                    'sources' => [],
                    'reputation' => $data['reputation'] ?? 'unknown',
                    'suspicious' => $data['suspicious'] ?? false,
                ];
            }
        } catch (\Exception $e) {
            Log::warning("EmailRep.io falhou: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Valida com Abstract API (100 verificações gratuitas/mês)
     */
    private function validateWithAbstract(string $email): ?array
    {
        if (empty($this->config['abstract_api_key'])) {
            return null;
        }
        
        try {
            $response = $this->client->get('https://emailvalidation.abstractapi.com/v1/', [
                'query' => [
                    'api_key' => $this->config['abstract_api_key'],
                    'email' => $email,
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if (isset($data['email'])) {
                return [
                    'valid' => $data['is_valid_format']['value'] ?? false,
                    'disposable' => $data['is_disposable_email']['value'] ?? false,
                    'free' => $data['is_free_email']['value'] ?? false,
                    'deliverable' => $data['deliverability'] === 'DELIVERABLE',
                    'smtp_check' => $data['is_smtp_valid']['value'] ?? false,
                    'score' => $data['quality_score'] ?? 0,
                    'sources' => [],
                ];
            }
        } catch (\Exception $e) {
            Log::warning("Abstract API falhou: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Valida com Mailboxlayer (100 verificações gratuitas/mês)
     */
    private function validateWithMailboxlayer(string $email): ?array
    {
        if (empty($this->config['mailboxlayer_key'])) {
            return null;
        }
        
        try {
            $response = $this->client->get('http://apilayer.net/api/check', [
                'query' => [
                    'access_key' => $this->config['mailboxlayer_key'],
                    'email' => $email,
                    'smtp' => 1,
                    'format' => 1,
                ]
            ]);
            
            $data = json_decode($response->getBody(), true);
            
            if (isset($data['email'])) {
                return [
                    'valid' => $data['format_valid'] ?? false,
                    'disposable' => $data['disposable'] ?? false,
                    'free' => $data['free'] ?? false,
                    'deliverable' => $data['smtp_check'] ?? false,
                    'smtp_check' => $data['smtp_check'] ?? false,
                    'score' => $data['score'] ?? 0,
                    'sources' => [],
                ];
            }
        } catch (\Exception $e) {
            Log::warning("Mailboxlayer falhou: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Validação básica quando APIs falham
     */
    private function basicValidation(string $email): array
    {
        // Validação de formato básica
        $isValidFormat = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
        
        // Lista de domínios descartáveis conhecidos
        $disposableDomains = [
            'tempmail.com', '10minutemail.com', 'guerrillamail.com',
            'mailinator.com', 'throwawaymail.com', 'yopmail.com',
            'fakeinbox.com', 'trashmail.com', 'getairmail.com',
            'maildrop.cc', 'temp-mail.org', 'sharklasers.com',
        ];
        
        $domain = substr(strrchr($email, "@"), 1);
        $isDisposable = in_array($domain, $disposableDomains);
        
        // Domínios de email grátis conhecidos
        $freeDomains = [
            'gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com',
            'icloud.com', 'aol.com', 'protonmail.com', 'zoho.com',
            'mail.com', 'yandex.com', 'gmx.com',
        ];
        
        $isFree = in_array($domain, $freeDomains);
        
        // Verificar MX records (mais confiável que formato)
        $hasMxRecord = false;
        if ($isValidFormat) {
            $hasMxRecord = checkdnsrr($domain, 'MX');
        }
        
        return [
            'valid' => $isValidFormat,
            'disposable' => $isDisposable,
            'free' => $isFree,
            'deliverable' => $hasMxRecord,
            'smtp_check' => false,
            'score' => $isValidFormat ? ($hasMxRecord ? 70 : 30) : 0,
            'sources' => [],
            'provider' => 'basic',
        ];
    }
    
    /**
     * Calcula score baseado nos dados do EmailRep
     */
    private function calculateScoreFromEmailRep(array $data): int
    {
        $score = 50;
        
        // Bonificações
        if ($data['details']['valid_format'] ?? false) $score += 20;
        if ($data['details']['smtp_valid'] ?? false) $score += 20;
        if ($data['details']['profiles_match'] ?? false) $score += 10;
        
        // Penalizações
        if ($data['details']['disposable'] ?? false) $score -= 40;
        if ($data['details']['spam'] ?? false) $score -= 30;
        if ($data['suspicious'] ?? false) $score -= 50;
        
        return max(0, min(100, $score));
    }
    
    /**
     * Validação rápida para formulários (com timeout curto)
     */
    public function quickValidate(string $email): bool
    {
        try {
            // Primeiro tentar EmailRep (mais rápido e gratuito)
            $result = $this->validateWithEmailRep($email);
            
            if ($result) {
                return $result['valid'] && !$result['disposable'];
            }
            
            // Fallback para validação básica
            $basic = $this->basicValidation($email);
            return $basic['valid'] && !$basic['disposable'];
            
        } catch (\Exception $e) {
            Log::error("Erro na validação rápida do email {$email}: " . $e->getMessage());
            return $this->basicValidation($email)['valid'];
        }
    }
    
    /**
     * Obtém detalhes completos do email
     */
    public function getEmailDetails(string $email): array
    {
        return $this->validateEmail($email);
    }
    
    /**
     * Simulação para testes (sem APIs)
     */
    public function mockValidateEmail(string $email): array
    {
        // Simulação para testes
        $domain = substr(strrchr($email, "@"), 1);
        
        // Lista de domínios para simulação
        $corporateDomains = ['company.com', 'business.co.ao', 'enterprise.gov.ao', 'empresa.ao'];
        $disposableDomains = ['tempmail.com', '10minutemail.com', 'mailinator.com'];
        $freeDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
        
        $isCorporate = in_array($domain, $corporateDomains);
        $isDisposable = in_array($domain, $disposableDomains);
        $isFree = in_array($domain, $freeDomains);
        $hasMx = checkdnsrr($domain, 'MX');
        
        return [
            'valid' => filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
            'disposable' => $isDisposable,
            'free' => $isFree,
            'deliverable' => $hasMx,
            'smtp_check' => $hasMx,
            'score' => $isCorporate ? 90 : ($isFree ? 70 : 50),
            'sources' => [],
            'provider' => 'mock',
        ];
    }
    
    /**
     * Verifica apenas se o email é válido (sem cache)
     */
    public function isValidEmail(string $email): bool
    {
        $result = $this->validateEmail($email);
        return $result['valid'] && !$result['disposable'];
    }
}