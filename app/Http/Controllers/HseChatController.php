<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HseChatController extends Controller
{
    public function index()
    {
        return view('chatbot.gpt');
    }

    public function stream(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'history' => ['nullable', 'array'],
        ]);

        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) {
            return response("OPENAI_API_KEY não configurada.", 500);
        }

        $model = env('OPENAI_MODEL', 'gpt-4o-mini');

        // Histórico vindo do frontend (mantém contexto)
        $history = $request->input('history', []);

        // Mensagem do user
        $userMessage = $request->input('message');

        // System prompt: “persona” HSE Angola
        $system = [
            "role" => "system",
            "content" => "Você é um Chatbot HSE & ESG do Portal HSE Angola. Responda em português, com foco em Angola. Dê respostas práticas, com passos, exemplos, e quando possível sugira links internos do portal (Notícias, Vagas, Links Úteis). Se o utilizador pedir consultoria/serviço, peça nome, e-mail e telefone (lead). Evite aconselhamento médico/jurídico definitivo; recomende procurar especialista quando necessário."
        ];

        $messages = array_merge([$system], $history, [
            ["role" => "user", "content" => $userMessage]
        ]);

        // SSE headers
        $headers = [
            "Content-Type" => "text/event-stream",
            "Cache-Control" => "no-cache",
            "Connection" => "keep-alive",
            "X-Accel-Buffering" => "no", // ajuda em Nginx
        ];

        return response()->stream(function () use ($apiKey, $model, $messages) {

            $payload = [
                // Usando Chat Completions streaming (estável e simples)
                // Também dá para fazer com Responses API, mas aqui vai a implementação mais direta.
                "model" => $model,
                "messages" => $messages,
                "stream" => true,
                "temperature" => 0.3,
            ];

            $client = Http::withHeaders([
                "Authorization" => "Bearer " . $apiKey,
                "Content-Type"  => "application/json",
            ])->withOptions([
                "stream" => true,
            ]);

            $response = $client->post("https://api.openai.com/v1/chat/completions", $payload);

            if (!$response->successful()) {
                echo "event: error\n";
                echo "data: " . json_encode(["message" => "Erro na API OpenAI: " . $response->body()]) . "\n\n";
                @ob_flush(); @flush();
                return;
            }

            $body = $response->toPsrResponse()->getBody();
            $buffer = '';

            while (!$body->eof()) {
                $chunk = $body->read(1024);
                if ($chunk === '') continue;

                $buffer .= $chunk;

                // A stream vem em linhas "data: {...}\n\n"
                while (($pos = strpos($buffer, "\n")) !== false) {
                    $line = substr($buffer, 0, $pos);
                    $buffer = substr($buffer, $pos + 1);

                    $line = trim($line);

                    if ($line === '' || !str_starts_with($line, 'data:')) continue;

                    $data = trim(substr($line, 5));

                    if ($data === '[DONE]') {
                        echo "event: done\n";
                        echo "data: {}\n\n";
                        @ob_flush(); @flush();
                        return;
                    }

                    $json = json_decode($data, true);
                    $delta = $json['choices'][0]['delta']['content'] ?? '';

                    if ($delta !== '') {
                        echo "event: token\n";
                        echo "data: " . json_encode(["text" => $delta]) . "\n\n";
                        @ob_flush(); @flush();
                    }
                }
            }
        }, 200, $headers);
    }
}
