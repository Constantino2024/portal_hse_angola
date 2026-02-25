@extends($layout)

@section('title', 'Chat')

@section('content')
<div class="container py-4 mt-8">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h1 class="fw-bold mb-1"><i class="fa-solid fa-comments me-2"></i>Chat</h1>
            <div class="text-muted">Conversas privadas entre profissionais e empresas (tempo real).</div>
        </div>
        <div class="mt-3 mt-md-0">
            <button type="button" class="btn btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#newChatModal">
                <i class="fa-solid fa-plus me-2"></i>Nova conversa
            </button>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body">
            @if($conversations->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3"><i class="fa-regular fa-message fa-3x text-muted"></i></div>
                    <h5 class="fw-bold">Ainda não há conversas</h5>
                    <p class="text-muted mb-0">Use o botão <strong>Mensagem</strong> no Banco de Talentos para iniciar uma conversa.</p>
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($conversations as $c)
                        @php
                            $other = $c->participants->first();
                            $name = $other?->name ?? 'Utilizador';
                        @endphp
                        <a href="{{ route('chat.show', $c) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:44px;height:44px; border:1px solid rgba(0,0,0,.08)">
                                    <i class="fa-solid fa-user text-muted"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $name }}</div>
                                    @php
                                        $last = $c->lastMessage;
                                        $preview = $last ? \Illuminate\Support\Str::limit($last->body, 60) : null;
                                        $myRead = $c->my_last_read_message_id;
                                        $isUnread = $c->last_message_id && (!$myRead || (int)$myRead < (int)$c->last_message_id) && $last && (int)$last->sender_id !== (int)auth()->id();
                                    @endphp
                                    <div class="small text-muted">
                                        @if($preview)
                                            {{ $preview }}
                                            <span class="mx-1">·</span>
                                        @endif
                                        Última atividade: {{ optional($c->last_message_at)->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            @if($isUnread)
                                <span class="badge rounded-pill bg-primary me-2">Novo</span>
                            @endif
                            <i class="fa-solid fa-chevron-right text-muted"></i>
                        </a>
                    @endforeach
                </div>
                <div class="mt-3">
                    {{ $conversations->links('pagination.custom') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal: Nova conversa -->
<div class="modal fade" id="newChatModal" tabindex="-1" aria-labelledby="newChatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold" id="newChatModalLabel"><i class="fa-solid fa-pen-to-square me-2"></i>Nova conversa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label small text-muted mb-1">Pesquisar utilizador (nome ou e-mail)</label>
          <input type="text" id="chatUserSearch" class="form-control rounded-pill" placeholder="Ex.: Maria, empresa@dominio.com" autocomplete="off">
          <div class="form-text">Pesquisar é opcional. Use 2+ caracteres para filtrar.</div>
        </div>

        <div id="chatUserResults" class="list-group list-group-flush border rounded-4 overflow-hidden" style="max-height: 300px; overflow:auto;">
          <div class="p-3 text-muted small">A carregar utilizadores…</div>
        </div>

        <div id="chatUserError" class="alert alert-danger mt-3 d-none"></div>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<style>
  .chat-user-avatar{ width:42px; height:42px; border-radius:9999px; overflow:hidden; border:1px solid rgba(0,0,0,.08); background:#f8f9fa; display:flex; align-items:center; justify-content:center; flex:0 0 auto; }
  .chat-user-avatar img{ width:100%; height:100%; object-fit:cover; display:block; }
  .chat-user-initials{ font-weight:700; color:#6c757d; }
  .chat-user-badge{ font-size:.72rem; }
  .chat-badge-company{ background:#ff7a00; color:#fff; }
  .chat-badge-pro{ background:#0d6efd; color:#fff; }
</style>
<script>
(function(){
  const modalEl = document.getElementById('newChatModal');
  const searchEl = document.getElementById('chatUserSearch');
  const resultsEl = document.getElementById('chatUserResults');
  const errorEl = document.getElementById('chatUserError');

  if (!modalEl || !searchEl || !resultsEl) return;

  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  let timer = null;

  function showError(msg){
    if(!errorEl) return;
    errorEl.textContent = msg;
    errorEl.classList.remove('d-none');
  }
  function clearError(){
    if(!errorEl) return;
    errorEl.textContent = '';
    errorEl.classList.add('d-none');
  }

  function renderEmpty(text){
    resultsEl.innerHTML = '<div class="p-3 text-muted small">'+text+'</div>';
  }

  function escapeHtml(str){
    return String(str ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function badgeHtml(u){
    const role = (u.role || '').toLowerCase();
    const label = u.badge || (role === 'empresa' ? 'Empresa' : 'Profissional');
    const cls = role === 'empresa' ? 'chat-badge-company' : 'chat-badge-pro';
    return `<span class="badge rounded-pill chat-user-badge ${cls}">${escapeHtml(label)}</span>`;
  }

  function avatarHtml(u){
    const url = u.avatar_url || '';
    if (url) {
      return `<div class="chat-user-avatar"><img src="${escapeHtml(url)}" alt="Avatar"></div>`;
    }
    const initials = (u.initials || (u.name ? String(u.name).trim().charAt(0).toUpperCase() : '?'));
    return `<div class="chat-user-avatar"><span class="chat-user-initials">${escapeHtml(initials)}</span></div>`;
  }

  async function searchUsers(q){
    clearError();
    if (q.length < 2) {
      renderEmpty('Pesquisar é opcional. Use 2+ caracteres para filtrar.');
      return;
    }
    resultsEl.innerHTML = '<div class="p-3 text-muted small"><i class="fa-solid fa-spinner fa-spin me-2"></i>A pesquisar…</div>';
    try{
      const resp = await fetch("{{ route('chat.users') }}?q=" + encodeURIComponent(q), {
        headers: { 'Accept': 'application/json' }
      });
      if(!resp.ok) throw new Error('Falha na pesquisa');
      const data = await resp.json();

      if(!Array.isArray(data) || data.length === 0){
        renderEmpty('Nenhum utilizador encontrado.');
        return;
      }

      resultsEl.innerHTML = '';
      data.forEach(u => {
        const a = document.createElement('button');
        a.type = 'button';
        a.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3';
        a.innerHTML = `
          <div class="d-flex align-items-center gap-3">
            ${avatarHtml(u)}
            <div>
              <div class="fw-semibold d-flex align-items-center gap-2">
                <span>${escapeHtml(u.name || 'Utilizador')}</span>
                ${badgeHtml(u)}
              </div>
              <div class="small text-muted">${escapeHtml(u.email || '')}</div>
            </div>
          </div>
          <i class="fa-solid fa-arrow-right text-muted"></i>
        `;
        a.addEventListener('click', () => startConversation(u.id));
        resultsEl.appendChild(a);
      });
    }catch(e){
      renderEmpty('Não foi possível carregar os resultados.');
      showError('Erro ao pesquisar utilizadores. Tenta novamente.');
    }
  }

  async function startConversation(recipientId){
    clearError();
    if(!recipientId) return;

    try{
      const resp = await fetch("{{ route('chat.start') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({ recipient_id: recipientId })
      });

      if(!resp.ok){
        const txt = await resp.text();
        throw new Error(txt || 'Falha ao iniciar conversa');
      }

      const data = await resp.json();
      if(data && data.url){
        window.location.href = data.url;
        return;
      }
      throw new Error('Resposta inválida do servidor');
    }catch(e){
      showError('Não foi possível iniciar a conversa. Verifica permissões e tenta novamente.');
    }
  }

  modalEl.addEventListener('shown.bs.modal', () => {
    searchEl.value = '';
    renderEmpty('A carregar utilizadores…');
    clearError();
    setTimeout(() => searchEl.focus(), 50);
  });

  searchEl.addEventListener('input', (ev) => {
    const q = (ev.target.value || '').trim();
    clearTimeout(timer);
    timer = setTimeout(() => searchUsers(q), 300);
  });
})();
</script>
@endpush

@endsection
