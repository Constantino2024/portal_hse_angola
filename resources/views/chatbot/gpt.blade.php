@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    <div class="lg:col-span-2 bg-white border rounded-xl shadow-sm flex flex-col h-[78vh]">
      <div class="p-4 border-b flex items-center justify-between">
        <div>
          <h1 class="text-lg font-semibold">Chatbot HSE Inteligente</h1>
          <p class="text-sm text-gray-500">Pergunte sobre HSE, ESG, requisitos, boas práticas e procedimentos.</p>
        </div>
        <button id="btnClear" class="text-sm px-3 py-2 rounded-lg border hover:bg-gray-50">Limpar</button>
      </div>

      <div id="chat" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50"></div>

      <div class="p-4 border-t bg-white">
        <form id="form" class="flex gap-2">
          <input id="msg" class="flex-1 rounded-xl border px-4 py-3 focus:outline-none focus:ring"
                 placeholder="Digite a sua pergunta..." autocomplete="off" />
          <button class="px-4 py-3 rounded-xl bg-blue-900 text-white hover:opacity-90">Enviar</button>
        </form>
        <p class="mt-2 text-xs text-gray-500">
          Este chatbot fornece orientação geral. Para casos críticos, consulte um especialista.
        </p>
      </div>
    </div>

    <div class="bg-white border rounded-xl shadow-sm p-4">
      <h2 class="font-semibold">Precisa de apoio profissional?</h2>
      <p class="text-sm text-gray-600 mt-1">Deixe o seu contacto e a nossa equipa pode falar consigo.</p>

      <form id="leadForm" class="mt-4 space-y-3">
        @csrf
        <input name="name" class="w-full rounded-xl border px-3 py-2" placeholder="Nome" />
        <input name="email" class="w-full rounded-xl border px-3 py-2" placeholder="Email" />
        <input name="phone" class="w-full rounded-xl border px-3 py-2" placeholder="Telefone" />
        <input name="company" class="w-full rounded-xl border px-3 py-2" placeholder="Empresa (opcional)" />
        <button class="w-full px-4 py-2 rounded-xl bg-orange-500 text-white hover:opacity-90">Enviar</button>
      </form>

      <div class="mt-6">
        <h3 class="font-semibold text-sm">Sugestões rápidas</h3>
        <div class="mt-2 flex flex-wrap gap-2">
          <button class="q px-3 py-2 rounded-xl border text-sm" data-q="O que é avaliação de risco e como aplicar numa obra?">Avaliação de risco</button>
          <button class="q px-3 py-2 rounded-xl border text-sm" data-q="Quais são os EPI’s mais comuns para construção civil?">EPI</button>
          <button class="q px-3 py-2 rounded-xl border text-sm" data-q="Explique ESG de forma simples e exemplos em Angola.">ESG</button>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
const chat = document.getElementById('chat');
const form = document.getElementById('form');
const msg = document.getElementById('msg');
const btnClear = document.getElementById('btnClear');
const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

let history = []; // [{role:"user"/"assistant", content:"..."}]

function bubble(text, who="bot") {
  const wrap = document.createElement('div');
  wrap.className = "flex " + (who === "me" ? "justify-end" : "justify-start");

  const b = document.createElement('div');
  b.className = (who === "me"
      ? "max-w-[80%] bg-blue-900 text-white rounded-2xl px-4 py-3"
      : "max-w-[80%] bg-white border rounded-2xl px-4 py-3");
  b.textContent = text;

  wrap.appendChild(b);
  chat.appendChild(wrap);
  chat.scrollTop = chat.scrollHeight;
  return b;
}

function setTyping() {
  const el = bubble("A escrever…", "bot");
  el.dataset.typing = "1";
  return el;
}

function clearChat(){
  chat.innerHTML = "";
  history = [];
}

btnClear.addEventListener('click', clearChat);

document.querySelectorAll('.q').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    msg.value = btn.dataset.q;
    msg.focus();
  });
});

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const text = msg.value.trim();
  if (!text) return;

  bubble(text, "me");
  history.push({ role: "user", content: text });

  msg.value = "";
  msg.focus();

  const typing = setTyping();
  let assistantText = "";

  // Faz POST que devolve SSE
  const res = await fetch("{{ route('chatbot.stream') }}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrf
    },
    body: JSON.stringify({ message: text, history })
  });

  if (!res.ok) {
    typing.textContent = "Erro ao contactar o chatbot.";
    return;
  }

  // Ler SSE manualmente
  const reader = res.body.getReader();
  const decoder = new TextDecoder("utf-8");
  let buffer = "";

  while (true) {
    const { value, done } = await reader.read();
    if (done) break;

    buffer += decoder.decode(value, { stream: true });

    // Eventos SSE separados por \n\n
    const parts = buffer.split("\n\n");
    buffer = parts.pop();

    for (const part of parts) {
      const lines = part.split("\n").filter(Boolean);
      let event = "message";
      let dataLine = "";

      for (const ln of lines) {
        if (ln.startsWith("event:")) event = ln.replace("event:", "").trim();
        if (ln.startsWith("data:")) dataLine += ln.replace("data:", "").trim();
      }

      if (event === "token") {
        const payload = JSON.parse(dataLine);
        assistantText += payload.text;
        typing.textContent = assistantText;
        chat.scrollTop = chat.scrollHeight;
      }

      if (event === "done") {
        typing.removeAttribute("data-typing");
        history.push({ role: "assistant", content: assistantText });
      }

      if (event === "error") {
        typing.textContent = "Erro: " + (JSON.parse(dataLine).message || "desconhecido");
      }
    }
  }
});
</script>
@endsection
