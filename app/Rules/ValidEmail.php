<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Rule;
use App\Services\EmailValidationService;

class ValidEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    private $emailValidationService;
    private $message;
    
    public function __construct()
    {
        $this->emailValidationService = new EmailValidationService();
    }
    
    public function passes($attribute, $value)
    {
        try {
            // Validação rápida primeiro
            if (!$this->emailValidationService->quickValidate($value)) {
                $this->message = 'O endereço de email não é válido.';
                return false;
            }
            
            // Validação completa (pode ser mais lenta)
            $details = $this->emailValidationService->getEmailDetails($value);
            
            if (!$details['valid']) {
                $this->message = 'O formato do email é inválido.';
                return false;
            }
            
            if ($details['disposable']) {
                $this->message = 'Emails temporários/descartáveis não são permitidos.';
                return false;
            }
            
            if (!$details['deliverable'] && !$details['smtp_check']) {
                $this->message = 'Não foi possível verificar a existência deste email. Por favor, use um email válido.';
                return false;
            }
            
            if ($details['score'] < 30) {
                $this->message = 'Este email possui uma reputação baixa. Por favor, use um email corporativo válido.';
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            // Em caso de erro na API, faz validação básica
            $this->message = 'Erro na verificação do email. Por favor, tente novamente.';
            return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
        }
    }
    
    public function message()
    {
        return $this->message;
    }
}
