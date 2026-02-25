<?php

namespace App\Http\Controllers;

use App\Models\ChatbotLead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function message(Request $request)
    {
        $data = $request->validate([
            'message' => ['required','string','max:2000'],
        ]);

        $msg = trim($data['message']);
        $lower = Str::lower($msg);

        // Respostas simples (MVP): dúvidas básicas + direcionamento para artigos/páginas internas.
        $reply = $this->buildReply($lower);

        return response()->json([
            'ok' => true,
            'reply' => $reply,
        ]);
    }

    public function lead(Request $request)
    {
        $data = $request->validate([
            'name'    => ['nullable','string','max:120'],
            'email'   => ['nullable','email','max:190'],
            'phone'   => ['nullable','string','max:40'],
            'company' => ['nullable','string','max:190'],
            'note'    => ['nullable','string','max:2000'],
        ]);

        // Pelo menos um contacto deve existir para ser lead
        if (empty($data['email']) && empty($data['phone'])) {
            return response()->json([
                'ok' => false,
                'message' => 'Informe pelo menos um contacto (email ou telefone).'
            ], 422);
        }

        $lead = ChatbotLead::create([
            'name'    => $data['name'] ?? null,
            'email'   => $data['email'] ?? null,
            'phone'   => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'note'    => $data['note'] ?? null,
            'ip'      => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Obrigado! Já registámos o seu contacto. Vamos falar consigo em breve.',
            'id' => $lead->id,
        ]);
    }

    private function buildReply(string $lower): array
    {
        // Conteúdo base
        $default = [
            'text' => "Posso ajudar com dúvidas de HSE/ESG, indicar recursos úteis e também registar o seu contacto caso queira falar com a nossa equipa.",
            'links' => [
                ['label' => 'Links úteis', 'url' => route('links.index')],
                ['label' => 'Vagas HSE', 'url' => route('jobs.index')],
                ['label' => 'Notícias', 'url' => route('posts.public')],
            ],
            'cta' => 'Se quiser, clique em “Quero ser contactado” e deixe o seu email/telefone.',
        ];

        // Regras rápidas por palavras-chave
        if (Str::contains($lower, ['epi','equipamento de prote', 'proteção individual'])) {
            return [
                'text' => "EPI significa *Equipamento de Protecção Individual*. É todo o equipamento utilizado pelo trabalhador para o proteger contra riscos que possam ameaçar a sua segurança e saúde (ex.: capacete, luvas, óculos, botas, arnês).

Dica: o EPI deve ser adequado ao risco, ter certificação aplicável e o trabalhador deve receber treino de uso.",
                'links' => [
                    ['label' => 'OSHA - PPE', 'url' => 'https://www.osha.gov/personal-protective-equipment'],
                    ['label' => 'HSE (UK) - PPE', 'url' => 'https://www.hse.gov.uk/toolbox/ppe.htm'],
                ],
                'cta' => 'Quer que eu te ajude a montar uma lista de EPI por actividade (obra, indústria, escritório, etc.)?',
            ];
        }

        if (Str::contains($lower, ['avaliação de risco','avaliacao de risco','risk assessment','matriz de risco'])) {
            return [
                'text' => "Uma avaliação de risco normalmente segue estes passos:
1) Identificar perigos
2) Identificar quem pode ser afectado e como
3) Avaliar risco (probabilidade x severidade) e definir controlos
4) Registar e implementar medidas
5) Rever periodicamente

Se me disseres a actividade (ex.: obra, laboratório, transporte, escritório), eu sugiro uma matriz e medidas de controlo.",
                'links' => [
                    ['label' => 'HSE (UK) - Risk assessment', 'url' => 'https://www.hse.gov.uk/simple-health-safety/risk/'],
                ],
                'cta' => 'Queres um modelo simples de matriz 5x5?',
            ];
        }

        if (Str::contains($lower, ['iso 45001','iso45001'])) {
            return [
                'text' => "A ISO 45001 é a norma internacional para Sistemas de Gestão de Segurança e Saúde no Trabalho (SST). Ajuda organizações a reduzir riscos, prevenir acidentes/doenças ocupacionais e melhorar desempenho.

Se me disseres o tipo de empresa, posso sugerir um plano de implementação em etapas.",
                'links' => [
                    ['label' => 'ISO - ISO 45001', 'url' => 'https://www.iso.org/iso-45001-occupational-health-and-safety.html'],
                ],
                'cta' => 'Queres um checklist inicial (política, perigos, objetivos, auditorias)?',
            ];
        }

        if (Str::contains($lower, ['esg','sustentabilidade'])) {
            return [
                'text' => "ESG é um conjunto de práticas e indicadores para avaliar desempenho *Ambiental (E)*, *Social (S)* e de *Governança (G)*.

No contexto empresarial, costuma envolver: compliance, ética, segurança, impacto ambiental, diversidade, relação com comunidade, transparência e reporte.",
                'links' => [
                    ['label' => 'OIT', 'url' => 'https://www.ilo.org/'],
                ],
                'cta' => 'Queres exemplos de iniciativas ESG que uma empresa pode publicar no portal?',
            ];
        }

        if (Str::contains($lower, ['contacto','contato','quero ser contactado','lead','orçamento','orcamento'])) {
            return [
                'text' => "Perfeito. Clica em **Quero ser contactado** e deixa o teu email ou telefone. Se quiseres, adiciona uma nota com o que precisas (empresa, sector, província, prazo).",
                'links' => [],
                'cta' => null,
                'showLeadForm' => true,
            ];
        }

        return $default;
    }
}
