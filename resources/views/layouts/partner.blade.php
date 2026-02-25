<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Área do Parceiro') · Portal HSE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1a365d">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @stack('styles')
    
    <style>
        :root {
            --primary-dark: #1a365d;
            --accent-orange: #d35400;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Better focus styles */
        *:focus {
            outline: 2px solid var(--accent-orange);
            outline-offset: 2px;
        }
        
        *:focus:not(.focus-visible) {
            outline: none;
        }
    </style>
</head>
<body class="admin-body">

<div class="admin-wrapper">

    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <div class="logo">
                Portal <strong>HSE</strong>
            </div>
            <div class="text-muted small mt-2" style="opacity: 0.8;">Área do Parceiro</div>
        </div>

        <nav class="sidebar-nav">
            <!-- Dashboard -->
            <a href="{{ route('partner.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('partner.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
                <span class="badge bg-accent-orange rounded-pill ms-auto"></span>
            </a>

            <!-- Publicar Vagas -->
            <a href="{{ route('partner.jobs.index') }}"
               class="sidebar-link {{ request()->routeIs('partner.jobs.*') ? 'active' : '' }}">
                <i class="fa-solid fa-briefcase"></i>
                <span>Publicar Vagas</span>
            </a>

            <!-- Iniciativas ESG -->
            <a href="{{ route('partner.esg.index') }}"
               class="sidebar-link {{ request()->routeIs('partner.esg.*') ? 'active' : '' }}">
                <i class="fa-solid fa-leaf"></i>
                <span>Iniciativas ESG</span>
            </a>

            <!-- Projectos -->
            <a href="{{ route('partner.projects.index') }}"
               class="sidebar-link {{ request()->routeIs('partner.projects.*') ? 'active' : '' }}">
                <i class="fa-solid fa-diagram-project"></i>
                <span>Projectos</span>
            </a>

            <!-- Separador -->
            <div class="sidebar-section-title">Banco de Talentos</div>

            <!-- Necessidades -->
            <a href="{{ route('partner.needs.index') }}"
               class="sidebar-link {{ request()->routeIs('partner.needs.*') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-list"></i>
                <span>Necessidades</span>
            </a>

            <!-- Pesquisar Talentos -->
            <a href="{{ route('partner.talents.index') }}"
               class="sidebar-link {{ request()->routeIs('partner.talents.*') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span>Pesquisar Talentos</span>
            </a>

            <!-- Chat -->
            <!--<a href="{{ route('chat.index') }}"
               class="sidebar-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                <i class="fa-solid fa-comments"></i>
                <span>Chat</span>
                        @if(!empty($chatUnreadCount))
                            <span class="badge rounded-pill bg-primary" style="margin-left:6px;">{{ $chatUnreadCount }}</span>
                        @endif
            </a>-->

            <hr style="border-color: rgba(255,255,255,0.15); margin: 1.5rem 1rem;">

            <!-- Ver site -->
            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                <i class="fa-solid fa-globe"></i>
                <span>Ver site</span>
                <i class="fa-solid fa-external-link-alt ms-auto" style="font-size: 0.75rem; opacity: 0.7;"></i>
            </a>

        </nav>

        <!-- User info at bottom -->
        <div class="sidebar-footer">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center" 
                     style="width: 44px; height: 44px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                    <i class="fa-regular fa-building text-white fs-5"></i>
                </div>
                <div class="ms-3">
                    <div class="small fw-semibold text-white">
                        {{ auth()->check() ? auth()->user()->name : 'Empresa' }}
                    </div>
                    <div class="small" style="opacity: 0.8; font-size: 0.75rem;">Conta Parceiro</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-content">

        <!-- Topbar -->
        <header class="admin-topbar">
            <button class="btn btn-sm btn-outline-secondary d-lg-none" id="toggleSidebar">
                <i class="fa-solid fa-bars me-1"></i> Menu
            </button>

            <div class="ms-auto d-flex align-items-center gap-3">
                
                
                <!-- User -->
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center" 
                            type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-2" 
                             style="width: 32px; height: 32px; background: var(--primary-dark);">
                            <i class="fa-regular fa-user text-white" style="font-size: 0.875rem;"></i>
                        </div>
                        <span>{{ Str::limit(auth()->check() ? auth()->user()->name : 'Empresa', 18) }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('partner.profile') }}">
                                <i class="fa-solid fa-user-gear me-3" style="width: 20px; color: var(--dark-gray);"></i>
                                <div>
                                    <div class="fw-semibold">Perfil</div>
                                    <small class="text-muted">Gerir sua conta</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('partner.profile.edit') }}">
                                <i class="fa-solid fa-pen-to-square me-3" style="width: 20px; color: var(--dark-gray);"></i>
                                <div>
                                    <div class="fw-semibold">Editar Perfil</div>
                                    <small class="text-muted">Atualizar dados da empresa</small>
                                </div>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-2"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                                    <i class="fa-solid fa-right-from-bracket me-3" style="width: 20px;"></i>
                                    <div class="fw-semibold">Sair</div>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <section class="admin-page-content">
            @include('partner.partials.alerts')
            @yield('content')
        </section>

        <!-- Footer -->
        <footer class="admin-footer">
            <div>
                © {{ date('Y') }} Portal HSE. Todos os direitos reservados.
                <span class="d-none d-md-inline"> · </span>
                <span class="d-block d-md-inline mt-1 mt-md-0">Área do Parceiro</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-primary-dark rounded-pill">v1.0.0</span>
                <span class="text-muted d-none d-md-inline">Ambiente de Produção</span>
            </div>
        </footer>

    </main>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar
    const toggleBtn = document.getElementById('toggleSidebar');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            document.body.classList.toggle('sidebar-open');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 992 && document.body.classList.contains('sidebar-open')) {
            const sidebar = document.querySelector('.admin-sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            if (sidebar && !sidebar.contains(event.target) && 
                toggleBtn && !toggleBtn.contains(event.target)) {
                document.body.classList.remove('sidebar-open');
            }
        }
    });
    
    // Add ripple effect to buttons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.7);
                transform: scale(0);
                animation: ripple 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // Add animation to stats cards on load
    document.querySelectorAll('.stats-card').forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate__animated', 'animate__fadeInUp');
    });
});

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

@if(config('broadcasting.default') === 'reverb')
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.0.1/dist/pusher.min.js"></script>
    <script>
        window.Echo = {
            private: function(channel) {
                console.log('Echo private channel:', channel);
                return {
                    listen: function(event, callback) {
                        console.log('Listening to:', event, 'on', channel);
                        return this;
                    },
                    listenForWhisper: function(event, callback) {
                        console.log('Listening for whisper:', event);
                        return this;
                    },
                    whisper: function(event, data) {
                        console.log('Whispering:', event, data);
                    }
                };
            }
        };
    </script>
    {{-- Para produção, use: --}}
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    {{-- E compile com Laravel Echo --}}
@endif

@stack('scripts')
</body>
</html>