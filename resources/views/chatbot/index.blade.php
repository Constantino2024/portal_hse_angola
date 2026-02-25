@extends('layouts.app')

@section('title', 'Chatbot HSE')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h1 class="mb-1"><i class="fa-solid fa-robot text-primary me-2"></i>Chatbot HSE Inteligente</h1>
            <p class="text-muted mb-0">MVP: respostas rápidas sobre HSE & ESG, indicação de recursos e captação de leads.</p>
        </div>
        <a href="{{ route('links.index') }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-link me-2"></i>Links Úteis
        </a>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="chatbot-card card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="chatbot-header px-4 py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="chatbot-badge"><i class="fa-solid fa-bolt"></i></span>
                                <div>
                                    <div class="fw-semibold">Assistente HSE</div>
                                    <div class="small text-muted">Respostas básicas + direcionamento</div>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-light" id="chatClear">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="chatbot-messages p-4" id="chatMessages" aria-live="polite">
                        <div class="chat-msg bot">
                            <div class="bubble">
                                Olá! Sou o assistente do Portal HSE. Pergunta-me algo como:
                                <div class="mt-2 d-flex flex-wrap gap-2">
                                    <button class="btn btn-sm btn-outline-secondary quick" data-q="O que é EPI?">O que é EPI?</button>
                                    <button class="btn btn-sm btn-outline-secondary quick" data-q="Como fazer avaliação de risco?">Avaliação de risco</button>
                                    <button class="btn btn-sm btn-outline-secondary quick" data-q="O que é ESG?">O que é ESG?</button>
                                    <button class="btn btn-sm btn-outline-secondary quick" data-q="Quero ser contactado">Quero ser contactado</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="chatbot-input px-4 py-3">
                        <form id="chatForm" class="d-flex gap-2">
                            @csrf
                            <input type="text" id="chatText" class="form-control form-control-lg rounded-4"
                                   placeholder="Escreve a tua dúvida..." maxlength="2000" autocomplete="off">
                            <button class="btn btn-primary btn-lg rounded-4" type="submit" id="chatSend">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </form>
                        <div class="small text-muted mt-2">
                            Dica: para falar com a nossa equipa, escreve “quero ser contactado”.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h3 class="h5 mb-3"><i class="fa-solid fa-user-check me-2 text-primary"></i>Captação de Leads</h3>
                    <p class="text-muted mb-3">Deixa o teu contacto e uma nota. A equipa do Portal HSE entra em contacto.</p>

                    <form id="leadForm" class="vstack gap-3">
                        @csrf
                        <div>
                            <label class="form-label">Nome</label>
                            <input type="text" name="name" class="form-control rounded-4" maxlength="120">
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control rounded-4" maxlength="190">
                        </div>
                        <div>
                            <label class="form-label">Telefone</label>
                            <input type="text" name="phone" class="form-control rounded-4" maxlength="40" placeholder="+244 ...">
                        </div>
                        <div>
                            <label class="form-label">Empresa</label>
                            <input type="text" name="company" class="form-control rounded-4" maxlength="190">
                        </div>
                        <div>
                            <label class="form-label">Nota</label>
                            <textarea name="note" class="form-control rounded-4" rows="4" maxlength="2000"
                                      placeholder="Ex.: preciso de consultoria HSE, auditoria, formação, etc."></textarea>
                        </div>

                        <button class="btn btn-primary rounded-4" type="submit" id="leadBtn">
                            <i class="fa-solid fa-envelope-circle-check me-2"></i>Quero ser contactado
                        </button>

                        <div class="alert alert-success d-none rounded-4" id="leadOk">
                            <i class="fa-solid fa-check me-2"></i>
                            Obrigado! Contacto registado.
                        </div>
                        <div class="alert alert-danger d-none rounded-4" id="leadErr"></div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body p-4">
                    <h3 class="h6 mb-2"><i class="fa-solid fa-circle-question me-2 text-primary"></i>O que o bot faz (MVP)</h3>
                    <ul class="text-muted mb-0">
                        <li>Responde dúvidas básicas de HSE e ESG</li>
                        <li>Indica links úteis e páginas do portal</li>
                        <li>Capta leads (contactos) para follow-up</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const chatText = document.getElementById('chatText');
    const chatSend = document.getElementById('chatSend');
    const chatClear = document.getElementById('chatClear');

    const leadForm = document.getElementById('leadForm');
    const leadBtn = document.getElementById('leadBtn');
    const leadOk = document.getElementById('leadOk');
    const leadErr = document.getElementById('leadErr');

    function esc(s) {
        return (s ?? '').replace(/[&<>"']/g, function (c) {
            return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]);
        });
    }

    function addMsg(who, html) {
        const wrap = document.createElement('div');
        wrap.className = 'chat-msg ' + who;
        wrap.innerHTML = `<div class="bubble">${html}</div>`;
        chatMessages.appendChild(wrap);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    async function sendMessage(text) {
        addMsg('me', esc(text));
        chatText.value = '';
        chatText.focus();

        const typingId = 'typing-' + Math.random().toString(16).slice(2);
        addMsg('bot', `<span id="${typingId}"><i class="fa-solid fa-spinner fa-spin me-2"></i>A pensar...</span>`);

        try {
            const res = await fetch("{{ route('chatbot.message') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({message: text})
            });

            const data = await res.json();
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();

            if (!res.ok || !data.ok) {
                addMsg('bot', 'Desculpa, tive um problema. Tenta novamente.');
                return;
            }

            const reply = data.reply || {};
            let html = esc(reply.text || '');

            if (Array.isArray(reply.links) && reply.links.length) {
                html += `<div class="mt-3 d-flex flex-wrap gap-2">` +
                    reply.links.map(l => `<a class="btn btn-sm btn-outline-primary rounded-4" target="_blank" rel="noopener" href="${esc(l.url)}">
                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>${esc(l.label)}
                    </a>`).join('') +
                    `</div>`;
            }

            if (reply.cta) {
                html += `<div class="mt-3 small text-muted"><i class="fa-solid fa-lightbulb me-1"></i>${esc(reply.cta)}</div>`;
            }

            addMsg('bot', html);

            if (reply.showLeadForm) {
                leadForm?.scrollIntoView({behavior:'smooth', block:'start'});
            }
        } catch (e) {
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();
            addMsg('bot', 'Sem ligação no momento. Verifica a internet e tenta novamente.');
        }
    }

    chatForm?.addEventListener('submit', function (e) {
        e.preventDefault();
        const text = (chatText?.value || '').trim();
        if (!text) return;
        sendMessage(text);
    });

    document.querySelectorAll('.quick').forEach(btn => {
        btn.addEventListener('click', function () {
            const q = this.getAttribute('data-q') || '';
            if (q) sendMessage(q);
        });
    });

    chatClear?.addEventListener('click', function () {
        // remove tudo menos a primeira mensagem (bot)
        const nodes = Array.from(chatMessages?.children || []);
        nodes.slice(1).forEach(n => n.remove());
    });

    leadForm?.addEventListener('submit', async function (e) {
        e.preventDefault();
        leadOk.classList.add('d-none');
        leadErr.classList.add('d-none');
        leadErr.textContent = '';

        const fd = new FormData(leadForm);
        leadBtn.disabled = true;
        leadBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>A enviar...';

        try {
            const res = await fetch("{{ route('chatbot.lead') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf || '',
                    'Accept': 'application/json'
                },
                body: fd
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok || !data.ok) {
                let msg = (data && data.message) ? data.message : 'Falha ao registar contacto.';
                leadErr.textContent = msg;
                leadErr.classList.remove('d-none');
            } else {
                leadOk.classList.remove('d-none');
                leadForm.reset();
                addMsg('bot', `<i class="fa-solid fa-check me-2"></i>${esc(data.message || 'Contacto registado!')}`);
            }
        } catch (err) {
            leadErr.textContent = 'Sem ligação no momento. Tenta novamente.';
            leadErr.classList.remove('d-none');
        } finally {
            leadBtn.disabled = false;
            leadBtn.innerHTML = '<i class="fa-solid fa-envelope-circle-check me-2"></i>Quero ser contactado';
        }
    });
})();
</script>
@endpush
