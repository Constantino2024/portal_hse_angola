@extends($layout)

@section('title', 'Chat')

@section('content')
<div class="container py-4 mt-8">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div>
            <div class="text-muted small mb-1">
                <a href="{{ route('chat.index') }}" class="text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
            <h2 class="fw-bold mb-0">
                <i class="fa-solid fa-message me-2"></i>
                Conversa{{ $other?->name ? ' com '.$other->name : '' }}
            </h2>
        </div>
    </div>

    @if(isset($paginator) && $paginator->currentPage() > 1)
        <div class="alert alert-secondary py-2 small">
            Estás a ver mensagens mais antigas (página {{ $paginator->currentPage() }}).
            <a href="{{ route('chat.show', $conversation) }}" class="ms-1">Voltar às mais recentes</a>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-0 rounded-top-4 d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                @if(isset($paginator) && $paginator->hasMorePages())
                    <a class="text-decoration-none" href="{{ $paginator->nextPageUrl() }}">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i>Ver mensagens anteriores
                    </a>
                @else
                    <span><i class="fa-solid fa-shield-halved me-1"></i>Chat privado</span>
                @endif
            </div>
            <div class="small text-muted">
                <span id="typingIndicator" style="display:none;"><i class="fa-solid fa-pen me-1"></i>Digitando…</span>
            </div>
        </div>

        <div id="chatMessages" class="card-body" style="height: 60vh; overflow-y:auto; padding: 1rem;">
            @forelse($messages as $m)
                @php $mine = auth()->id() === $m->sender_id; @endphp
                <div class="d-flex mb-2 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="p-3 rounded-4 {{ $mine ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 78%;">
                        <div class="small opacity-75 mb-1">
                            <span class="fw-semibold">{{ $mine ? 'Você' : ($m->sender?->name ?? 'Utilizador') }}</span>
                            <span class="mx-1">·</span>
                            <span>{{ optional($m->created_at)->format('d/m/Y H:i') }}</span>
                            @if($mine)
                                @php $read = isset($otherLastReadId) && $otherLastReadId && $m->id <= $otherLastReadId; @endphp
                                <span class="ms-2" data-msg-id="{{ $m->id }}">{!! $read ? '&#10003;&#10003;' : '&#10003;' !!}</span>
                            @endif
                        </div>
                        <div style="white-space: pre-wrap;">{{ $m->body }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="fa-regular fa-message fa-2x mb-2"></i>
                    <div>Nenhuma mensagem ainda. Envia a primeira.</div>
                </div>
            @endforelse
        </div>

        <div class="card-footer bg-white border-0 rounded-bottom-4">
            <form id="chatForm" action="{{ route('chat.send', $conversation) }}" method="POST" class="d-flex gap-2">
                @csrf
                <input type="text" name="body" id="chatBody" class="form-control form-control-lg rounded-4" placeholder="Escreva uma mensagem..." maxlength="2000" autocomplete="off" required>
                <button class="btn btn-primary btn-lg rounded-4 px-4" type="submit">
                    <i class="fa-solid fa-paper-plane me-1"></i>Enviar
                </button>
            </form>
            @error('body')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<script>
(function () {
    const messagesEl = document.getElementById('chatMessages');
    const form = document.getElementById('chatForm');
    const input = document.getElementById('chatBody');
    const typingEl = document.getElementById('typingIndicator');

    function scrollToBottom() {
        if (!messagesEl) return;
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }


    async function markRead() {
        try {
            await fetch('{{ route('chat.read', $conversation) }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({})
            });
        } catch (e) {}
    }


    function appendMessage(payload) {
        const mine = String(payload.sender_id) === String({{ (int)auth()->id() }});

        const wrapper = document.createElement('div');
        wrapper.className = 'd-flex mb-2 ' + (mine ? 'justify-content-end' : 'justify-content-start');

        const bubble = document.createElement('div');
        bubble.className = 'p-3 rounded-4 ' + (mine ? 'bg-primary text-white' : 'bg-light');
        bubble.style.maxWidth = '78%';

        const meta = document.createElement('div');
        meta.className = 'small opacity-75 mb-1';

        const name = mine ? 'Você' : (payload.sender_name || 'Utilizador');
        const date = payload.created_at ? new Date(payload.created_at) : new Date();
        const dd = date.toLocaleString('pt-PT', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });

        meta.innerHTML = '<span class="fw-semibold">' + name + '</span><span class="mx-1">·</span><span>' + dd + '</span>';

        if (mine) {
            const st = document.createElement('span');
            st.className = 'ms-2';
            st.setAttribute('data-msg-id', String(payload.id || ''));
            st.innerHTML = '&#10003;';
            meta.appendChild(st);
        }

        const body = document.createElement('div');
        body.style.whiteSpace = 'pre-wrap';
        body.textContent = payload.body || '';

        bubble.appendChild(meta);
        bubble.appendChild(body);
        wrapper.appendChild(bubble);
        messagesEl.appendChild(wrapper);
        scrollToBottom();
    markRead();
    }

    scrollToBottom();
    markRead();

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            const body = (input.value || '').trim();
            if (!body) return;

            input.disabled = true;

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ body })
                });

                const data = await res.json();
                if (!res.ok || !data.ok) {
                    alert((data && data.message) ? data.message : 'Não foi possível enviar a mensagem.');
                } else {
                    appendMessage({
                        ...data.message,
                        sender_name: 'Você'
                    });
                    input.value = '';
                }
            } catch (err) {
                alert('Erro de rede ao enviar a mensagem.');
            } finally {
                input.disabled = false;
                input.focus();
            }
        });
    }

    // Realtime (se Echo estiver configurado no projeto)
    if (window.Echo) {
        const channel = 'conversation.{{ $conversation->id }}';
        try {
            window.Echo.private(channel)
                .listen('.message.sent', (e) => {
                    appendMessage({
                        id: e.id,
                        body: e.body,
                        created_at: e.created_at,
                        sender_id: e.sender_id,
                        sender_name: e.sender_name
                    });
                    if (String(e.sender_id) !== String({{ (int)auth()->id() }})) {
                        markRead();
                    }
                })
                .listen('.message.read', (e) => {
                    // Update ✓✓ for messages up to message_id
                    const readerId = String(e.reader_id || '');
                    if (readerId === String({{ (int)auth()->id() }})) return;
                    const mid = parseInt(e.message_id || '0', 10);
                    if (!mid) return;
                    document.querySelectorAll('[data-msg-id]').forEach(el => {
                        const id = parseInt(el.getAttribute('data-msg-id') || '0', 10);
                        if (id && id <= mid) {
                            el.innerHTML = '&#10003;&#10003;';
                        }
                    });
                })
                .listenForWhisper('typing', () => {
                    if (!typingEl) return;
                    typingEl.style.display = 'inline';
                    clearTimeout(window.__typingTimeout);
                    window.__typingTimeout = setTimeout(() => {
                        typingEl.style.display = 'none';
                    }, 1200);
                });

            if (input) {
                let t = null;
                input.addEventListener('input', () => {
                    clearTimeout(t);
                    t = setTimeout(() => {
                        window.Echo.private(channel).whisper('typing', { t: Date.now() });
                    }, 400);
                });
            }
        } catch (e) {
            // silently ignore
        }
    }
})();
</script>
@endsection
