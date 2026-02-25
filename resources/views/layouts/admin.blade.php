<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin') · Portal HSE</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- Teu CSS principal --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body class="admin-body">

<div class="admin-wrapper">

    {{-- ================= SIDEBAR ================= --}}
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <span class="logo">
                Portal <strong>HSE</strong>
            </span>
        </div>

        <nav class="sidebar-nav">

            <a href="{{ route('admin.posts.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                <i class="fa-solid fa-newspaper"></i>
                <span>Notícias</span>
            </a>


            <a href="{{ route('admin.jobs.index') }}" 
                class="sidebar-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
                <i class="fa-solid fa-briefcase"></i> 
                <span>Vagas HSE</span>
            </a>

            <a href="{{ route('admin.agenda.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.agenda.*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i>
                <span>Agenda HSE & ESG</span>
            </a>

            

            <a href="{{ route('chat.index') }}"
               class="sidebar-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                <i class="fa-solid fa-comments"></i>
                <span>Chat</span>
                    @if(!empty($chatUnreadCount))
                        <span class="badge rounded-pill bg-primary" style="margin-left:6px;">{{ $chatUnreadCount }}</span>
                    @endif
            </a>

            <hr>

            {{-- No ficheiro da sidebar, adicione antes do <hr> --}}
            <a href="{{ route('admin.users.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-cog"></i>
                <span>Gestão de Utilizadores</span>
                @php
                    $inactiveCount = \App\Models\User::where('is_active', false)->count();
                @endphp
                @if($inactiveCount > 0)
                    <span class="badge rounded-pill bg-warning text-dark ms-auto">{{ $inactiveCount }}</span>
                @endif
            </a>

            <hr>
            
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                <i class="fa-solid fa-globe"></i>
                <span>Ver site</span>
            </a>

            <a href="#" class="sidebar-link">
              <form method="POST" action="{{ route("logout") }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-login" style="color:inherit;text-decoration:none">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
                    </button>
                </form>
            </a>

        </nav>
    </aside>

    {{-- ================= CONTEÚDO ================= --}}
    <main class="admin-content">

        {{-- Topbar --}}
        <header class="admin-topbar">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" id="toggleSidebar">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="ms-auto">
                <span class="admin-user">
                    <i class="fa-regular fa-user"></i> Administrador
                </span>
            </div>
        </header>

        {{-- Conteúdo --}}
        <section class="admin-page-content">
            @yield('content')
        </section>

    </main>

</div>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.body.classList.toggle('sidebar-open');
});
</script>

@stack('scripts')
</body>
</html>
