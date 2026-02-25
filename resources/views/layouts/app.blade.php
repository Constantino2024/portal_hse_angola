<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Portal HSE Angola - @yield('title', 'Início')</title>
    <meta name="description" content="@yield('meta_description', 'Portal HSE - Notícias sobre Saúde, Segurança e Ambiente')">
    @yield('meta_extra')
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap (para grid, forms, etc.) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome para ícones --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Seu estilo principal --}}
    <link rel="stylesheet" href="{{ asset('css/globals.css') }}">
    <link rel="stylesheet" href="{{ asset('css/postshow.css') }}">

    {{-- Novos estilos melhorados --}}
    <link rel="stylesheet" href="{{ asset('css/portal-enhancements.css') }}">

    @stack('styles')
</head>
<body>

    {{-- HEADER MELHORADO --}}
    <header>
        <div class="container header-container">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('img/logo.jpg') }}" alt="" width="150">
            </a>

            <div class="mobile-toggle" id="mobileToggle">
                <i class="fa-solid fa-bars"></i>
            </div>

            <ul class="nav-menu" id="navMenu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    Início
                </a></li>
                <li><a href="{{ route('posts.public') }}" class="{{ request()->routeIs('posts.public') ? 'active' : '' }}">
                     Notícias
                </a></li>
                <li><a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'active' : '' }}">
                    Agenda
                </a></li>
                <li><a href="{{ route('talent.index') }}" class="{{ request()->routeIs('talent.index') ? 'active' : '' }}">
                    Banco de Talentos
                </a></li>
                <li><a href="{{ route('jobs.index') }}" class="{{ request()->routeIs('jobs.*') ? 'active' : '' }}">
                    Vagas HSE
                </a></li>
                <li><a href="https://ticket.hseangola.com/" target="_blank">
                    Eventos
                </a></li>
                @auth
                <!--<li><a href="{{ route('chat.index') }}" class="{{ request()->routeIs('chat.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments me-1"></i> Chat
                    @if(!empty($chatUnreadCount))
                        <span class="badge rounded-pill bg-primary" style="margin-left:6px;">{{ $chatUnreadCount }}</span>
                    @endif
                </a></li>-->
                <li>
                    @php
                        $u = auth()->user();
                        $label = $u->isCompany() ? "Área da Empresa" : ($u->isAdmin() || $u->isEditor() ? "Admin" : "Meu Perfil");
                        $url = $u->isCompany() ? route("partner.dashboard") : ($u->isAdmin() || $u->isEditor() ? route("talent.profile.show") : route("talent.profile.show"));
                    @endphp
                    <a href="{{ route('talent.profile.show') }}" class="{{ request()->routeIs('talent.profile.show') ? 'active' : '' }}">
                        <i class="fa-solid fa-user me-1"></i> {{ $label }}
                    </a>
                </li>
                <li>
                    <form method="POST" action="{{ route("logout") }}" style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-login" style="color:inherit;text-decoration:none">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
                        </button>
                    </form>
                </li>
                @else
                <button class="btn btn-login">
                    <a href="{{ route("login") }}" class=""> Entrar
                    </a>
                </button>
                <!--<li><a href="{{ route("register") }}"><i class="fa-solid fa-user-plus me-1"></i> Registar</a></li>-->
                @endauth

            </ul>
        </div>
    </header>

    <main class="mt-8">
        @yield('content')
    </main>

    {{-- FOOTER PROFISSIONAL --}}
    <footer id="contactos">
        {{-- Newsletter Banner --}}
        <div class="footer-newsletter">
            <div class="container">
                <div class="newsletter-content">
                    <div class="newsletter-text">
                        <h4><i class="fas fa-envelope-open-text"></i> Fique por Dentro das Novidades HSE</h4>
                        <p>Receba conteúdo exclusivo, notícias e atualizações diretamente no seu email.</p>
                    </div>
                    <form action="{{ route('subscribers.store') }}" method="POST" class="newsletter-form">
                        @csrf
                        <div class="input-group">
                            <input type="email" 
                                name="email" 
                                placeholder="Seu melhor email" 
                                required
                                class="form-control">
                            <button type="submit" class="btn btn-accent">
                                <i class="fas fa-paper-plane"></i> Subscrever
                            </button>
                        </div>
                        <small class="form-text">Não enviamos spam. Cancele a qualquer momento.</small>
                    </form>
                </div>
            </div>
        </div>

        {{-- Main Footer --}}
        <div class="footer-main">
            <div class="container">
                <div class="footer-grid">
                    {{-- Coluna 1: Logo e Sobre --}}
                    <div class="footer-col">
                        <div class="footer-brand">
                            <div class="footer-logo">
                                <i class="fas fa-shield-alt"></i>
                                <div>
                                    <span class="logo-main">Portal</span>
                                    <span class="logo-highlight">HSE Angola</span>
                                </div>
                            </div>
                            <p class="footer-description">
                                Sua plataforma de referência em Saúde, Segurança e Ambiente em Angola.
                                Conteúdos técnicos, notícias e oportunidades para profissionais do setor.
                            </p>
                        </div>
                        
                        <div class="footer-social">
                            <h5>Siga-nos</h5>
                            <div class="social-icons">
                                <a target="_blank" href="https://web.facebook.com/profile.php?id=61582681874540&mibextid=wwXIfr&rdid=9Ry9rW1Hu6kxtyu9&share_url=https%3A%2F%2Fweb.facebook.com%2Fshare%2F1Bi1CwBeZB%2F%3Fmibextid%3DwwXIfr%26_rdc%3D1%26_rdr#" class="social-icon facebook" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a target="_blank" href="https://www.linkedin.com/in/calulo-global-316007360?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" class="social-icon linkedin" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a target="_blank" href="https://www.instagram.com/calulo_global?igsh=MW5jZG4zdzhoYnh0cA%3D%3D&utm_source=qr" class="social-icon instagram" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a target="_blank" href="https://www.youtube.com/@hsetv-g1i" class="social-icon youtube" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Coluna 2: Links Rápidos --}}
                    <div class="footer-col">
                        <h4 class="footer-title">Navegação</h4>
                        <ul class="footer-links">
                            <li>
                                <a href="{{ route('home') }}">
                                    <i class="fas fa-home"></i> Início
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('posts.public') }}">
                                    <i class="fas fa-newspaper"></i> Notícias
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('jobs.index') }}">
                                    <i class="fas fa-briefcase"></i> Vagas HSE
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('agenda.index') }}">
                                    <i class="fas fa-calendar-alt"></i> Agenda
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Coluna 3: Categorias --}}
                    <!--<div class="footer-col">
                        <h4 class="footer-title">Categorias</h4>
                        <ul class="footer-links">
                            <li><a href="#"><i class="fas fa-stethoscope"></i> Saúde Ocupacional</a></li>
                            <li><a href="#"><i class="fas fa-hard-hat"></i> Segurança no Trabalho</a></li>
                            <li><a href="#"><i class="fas fa-leaf"></i> Gestão Ambiental</a></li>
                            <li><a href="#"><i class="fas fa-gavel"></i> Legislação HSE</a></li>
                            <li><a href="#"><i class="fas fa-chart-line"></i> Indicadores</a></li>
                            <li><a href="#"><i class="fas fa-graduation-cap"></i> Formação</a></li>
                        </ul>
                    </div>-->

                    {{-- Coluna 4: Contactos --}}
                    <div class="footer-col">
                        <h4 class="footer-title">Contactos</h4>
                        <div class="contact-info">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="contact-details">
                                    <strong>Endereço</strong>
                                    <p>Nova Vida, Rua 2, edifício TKSE, 1º andar – Luanda, Angola</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-details">
                                    <strong>Telefone</strong>
                                    <p>+244 940 532 884</p>
                                    <p>+244 923 979 915</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-details">
                                    <strong>Email</strong>
                                    <p>geral@portalseangola.co.ao</p>
                                    <p>contacto@caluloglobal.co.ao</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <p>&copy; {{ date('Y') }} Portal HSE Angola. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    {{-- Toast de feedback --}}
    @if(session('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
            <div class="toast align-items-center text-bg-success border-0 show"
                 role="alert" aria-live="assertive" aria-atomic="true" id="globalToast">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                            data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toast
            const toastEl = document.getElementById('globalToast');
            if (toastEl) {
                const toast = new bootstrap.Toast(toastEl, { 
                    delay: 4000,
                    animation: true 
                });
                toast.show();
            }

            // Loading nos botões de formulário
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function () {
                    const btn = form.querySelector('button[type="submit"]');
                    if (btn && !btn.dataset.loadingSet) {
                        btn.dataset.loadingSet = 'true';
                        btn.dataset.originalText = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Aguarde...';
                        btn.disabled = true;
                    }
                });
            });

            // Menu mobile
            const mobileToggle = document.getElementById('mobileToggle');
            const navMenu = document.getElementById('navMenu');

            if (mobileToggle && navMenu) {
                mobileToggle.addEventListener('click', () => {
                    navMenu.classList.toggle('active');
                    mobileToggle.innerHTML = navMenu.classList.contains('active') 
                        ? '<i class="fa-solid fa-times"></i>' 
                        : '<i class="fa-solid fa-bars"></i>';
                });
            }

            // Fechar menu ao clicar em um link
            document.querySelectorAll('.nav-menu a').forEach(link => {
                link.addEventListener('click', () => {
                    navMenu.classList.remove('active');
                    mobileToggle.innerHTML = '<i class="fa-solid fa-bars"></i>';
                });
            });

            // Smooth scroll para âncoras
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    if(this.getAttribute('href') === '#') return;
                    
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if(target) {
                        window.scrollTo({
                            top: target.offsetTop - 100,
                            behavior: 'smooth'
                        });
                    }
                });
            });

            // Contador de visualizações (simulado)
            const counters = document.querySelectorAll('.view-count');
            counters.forEach(counter => {
                const count = parseInt(counter.textContent);
                if(!isNaN(count) && count > 1000) {
                    counter.innerHTML = `<i class="fas fa-fire text-danger me-1"></i>${(count/1000).toFixed(1)}K visualizações`;
                }
            });
        });

        // Scroll to top button functionality
const scrollTopBtn = document.getElementById('scrollTopBtn');
if (scrollTopBtn) {
    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            scrollTopBtn.style.opacity = '1';
            scrollTopBtn.style.visibility = 'visible';
        } else {
            scrollTopBtn.style.opacity = '0';
            scrollTopBtn.style.visibility = 'hidden';
        }
    });
    
    scrollTopBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Animate footer columns on scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animationPlayState = 'running';
        }
    });
}, {
    threshold: 0.1
});

document.querySelectorAll('.footer-col').forEach(col => {
    col.style.animationPlayState = 'paused';
    observer.observe(col);
});

// Newsletter form enhancement
const newsletterForm = document.querySelector('.newsletter-form');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn && !submitBtn.dataset.loading) {
            submitBtn.dataset.loading = 'true';
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> A processar...';
            submitBtn.disabled = true;
        }
    });
}
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