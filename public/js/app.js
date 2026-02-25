
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // HERO CAROUSEL - JAVASCRIPT COMPLETO
    // ============================================
    
    const carousel = document.getElementById('modernHeroCarousel');
    if (!carousel) return;
    
    // Inicializar carousel Bootstrap
    const bsCarousel = new bootstrap.Carousel(carousel, {
        interval: 8000,
        wrap: true,
        touch: true,
        pause: 'hover'
    });
    
    // Elementos do DOM
    const prevBtn = carousel.querySelector('.carousel-control-prev');
    const nextBtn = carousel.querySelector('.carousel-control-next');
    const indicators = carousel.querySelectorAll('.carousel-indicators-container button');
    const progressBars = carousel.querySelectorAll('.indicator-progress');
    const currentCounter = carousel.querySelector('.counter-current');
    const totalCounter = carousel.querySelector('.counter-total');
    const bgImages = carousel.querySelectorAll('.carousel-bg-image');
    
    // Estado do carousel
    let currentSlide = 0;
    let isAnimating = false;
    let progressInterval;
    let isPaused = false;
    
    // Inicializar
    initCarousel();
    
    function initCarousel() {
        // Configurar contador total
        if (totalCounter) {
            totalCounter.textContent = String(indicators.length).padStart(2, '0');
        }
        
        // Pré-carregar imagens
        preloadImages();
        
        // Iniciar progress bar para o slide ativo
        startProgressBar();
        
        // Configurar eventos
        setupEventListeners();
        
        // Iniciar animação de zoom
        startZoomAnimation();
    }
    
    function preloadImages() {
        bgImages.forEach(img => {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', function() {
                    this.classList.add('loaded');
                });
                img.addEventListener('error', function() {
                    this.style.display = 'none';
                    const parent = this.parentElement;
                    if (parent) {
                        const placeholder = document.createElement('div');
                        placeholder.className = 'carousel-placeholder';
                        placeholder.style.cssText = `
                            width: 100%;
                            height: 100%;
                            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-size: 2rem;
                        `;
                        placeholder.innerHTML = '<i class="fas fa-newspaper"></i>';
                        parent.appendChild(placeholder);
                    }
                });
            }
        });
    }
    
    function setupEventListeners() {
        // Botões de navegação
        if (prevBtn) {
            prevBtn.addEventListener('click', handlePrevClick);
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', handleNextClick);
        }
        
        // Indicadores
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => handleIndicatorClick(index));
        });
        
        // Eventos do carousel Bootstrap
        carousel.addEventListener('slide.bs.carousel', handleSlideStart);
        carousel.addEventListener('slid.bs.carousel', handleSlideEnd);
        
        // Teclado
        document.addEventListener('keydown', handleKeyboardNavigation);
        
        // Touch/swipe
        setupTouchEvents();
        
        // Visibility API (pause quando aba estiver oculta)
        document.addEventListener('visibilitychange', handleVisibilityChange);
    }
    
    function handlePrevClick() {
        if (isAnimating) return;
        bsCarousel.prev();
        resetProgressBar();
    }
    
    function handleNextClick() {
        if (isAnimating) return;
        bsCarousel.next();
        resetProgressBar();
    }
    
    function handleIndicatorClick(index) {
        if (isAnimating || index === currentSlide) return;
        bsCarousel.to(index);
        resetProgressBar();
    }
    
    function handleSlideStart(event) {
        isAnimating = true;
        stopProgressBar();
        
        // Atualizar indicadores visuais
        const newIndex = event.to;
        updateIndicators(newIndex);
        updateCounter(newIndex);
        
        // Animar saída do slide atual
        animateSlideOut(event.from);
    }
    
    function handleSlideEnd(event) {
        currentSlide = event.to;
        
        // Animar entrada do novo slide
        animateSlideIn(currentSlide);
        
        // Iniciar progress bar do novo slide
        setTimeout(() => {
            isAnimating = false;
            if (!isPaused) {
                startProgressBar();
            }
        }, 500);
    }
    
    function handleKeyboardNavigation(event) {
        if (event.key === 'ArrowLeft') {
            handlePrevClick();
        } else if (event.key === 'ArrowRight') {
            handleNextClick();
        } else if (event.key === ' ') {
            event.preventDefault();
            togglePause();
        }
    }
    
    function handleVisibilityChange() {
        if (document.hidden) {
            pauseCarousel();
        } else {
            resumeCarousel();
        }
    }
    
    function setupTouchEvents() {
        let touchStartX = 0;
        let touchEndX = 0;
        const minSwipeDistance = 50;
        
        carousel.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        carousel.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const distance = touchStartX - touchEndX;
            
            if (Math.abs(distance) < minSwipeDistance) return;
            
            if (distance > 0) {
                // Swipe left = next
                handleNextClick();
            } else {
                // Swipe right = prev
                handlePrevClick();
            }
        }
    }
    
    // Progress bar functions
    function startProgressBar() {
        stopProgressBar();
        
        const activeIndicator = indicators[currentSlide];
        if (!activeIndicator) return;
        
        const progressBar = activeIndicator.querySelector('.indicator-progress');
        if (!progressBar) return;
        
        progressBar.style.transition = 'none';
        progressBar.style.width = '0%';
        
        // Forçar reflow
        progressBar.offsetHeight;
        
        progressBar.style.transition = `width ${bsCarousel._config.interval}ms linear`;
        progressBar.style.width = '100%';
        
        progressInterval = setTimeout(() => {
            if (!isPaused) {
                bsCarousel.next();
                resetProgressBar();
            }
        }, bsCarousel._config.interval);
    }
    
    function stopProgressBar() {
        if (progressInterval) {
            clearTimeout(progressInterval);
            progressInterval = null;
        }
        
        const activeIndicator = indicators[currentSlide];
        if (activeIndicator) {
            const progressBar = activeIndicator.querySelector('.indicator-progress');
            if (progressBar) {
                progressBar.style.transition = 'none';
                progressBar.style.width = '0%';
            }
        }
    }
    
    function resetProgressBar() {
        stopProgressBar();
        if (!isPaused) {
            setTimeout(startProgressBar, 100);
        }
    }
    
    // UI update functions
    function updateIndicators(newIndex) {
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === newIndex);
        });
    }
    
    function updateCounter(newIndex) {
        if (currentCounter) {
            currentCounter.textContent = String(newIndex + 1).padStart(2, '0');
        }
    }
    
    // Animation functions
    function animateSlideOut(fromIndex) {
        const slide = carousel.querySelectorAll('.carousel-item')[fromIndex];
        if (slide) {
            const content = slide.querySelector('.carousel-card');
            if (content) {
                content.style.animation = 'fadeOut 0.5s ease forwards';
            }
        }
    }
    
    function animateSlideIn(toIndex) {
        const slide = carousel.querySelectorAll('.carousel-item')[toIndex];
        if (slide) {
            const content = slide.querySelector('.carousel-card');
            if (content) {
                content.style.animation = 'none';
                // Forçar reflow
                content.offsetHeight;
                content.style.animation = 'fadeInUp 0.8s ease-out forwards';
            }
        }
    }
    
    function startZoomAnimation() {
        const activeSlide = carousel.querySelector('.carousel-item.active');
        if (activeSlide) {
            const bgImage = activeSlide.querySelector('.carousel-bg-image');
            if (bgImage) {
                bgImage.style.animationPlayState = 'running';
            }
        }
    }
    
    function pauseZoomAnimation() {
        const activeSlide = carousel.querySelector('.carousel-item.active');
        if (activeSlide) {
            const bgImage = activeSlide.querySelector('.carousel-bg-image');
            if (bgImage) {
                bgImage.style.animationPlayState = 'paused';
            }
        }
    }
    
    // Control functions
    function togglePause() {
        if (isPaused) {
            resumeCarousel();
        } else {
            pauseCarousel();
        }
    }
    
    function pauseCarousel() {
        isPaused = true;
        stopProgressBar();
        pauseZoomAnimation();
        
        // Adicionar ícone de pause
        const activeIndicator = indicators[currentSlide];
        if (activeIndicator) {
            const progressBar = activeIndicator.querySelector('.indicator-progress');
            if (progressBar) {
                progressBar.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
            }
        }
    }
    
    function resumeCarousel() {
        if (!isPaused) return;
        
        isPaused = false;
        startProgressBar();
        startZoomAnimation();
        
        // Remover ícone de pause
        const activeIndicator = indicators[currentSlide];
        if (activeIndicator) {
            const progressBar = activeIndicator.querySelector('.indicator-progress');
            if (progressBar) {
                progressBar.style.backgroundColor = '';
            }
        }
    }
    
    // Adicionar CSS para animações
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }
            to {
                opacity: 0;
                transform: translateY(20px);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
    `;
    document.head.appendChild(style);
    
    // Auto-pause quando não estiver visível
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) {
                pauseCarousel();
            } else {
                resumeCarousel();
            }
        });
    }, { threshold: 0.5 });
    
    observer.observe(carousel);
    
    // Inicializar tooltips para indicadores (se necessário)
    if (typeof bootstrap.Tooltip !== 'undefined') {
        indicators.forEach(indicator => {
            const title = indicator.querySelector('.indicator-label')?.textContent;
            if (title) {
                new bootstrap.Tooltip(indicator, {
                    title: title,
                    placement: 'top',
                    trigger: 'hover'
                });
            }
        });
    }
});

// ===========================================
// SCROLL INFINITO HSE - DADOS DINÂMICOS
// ===========================================

class HSEAngolaTicker {
    constructor() {
        this.tickerWrapper = document.getElementById('tickerWrapper');
        this.tickerProvinces = document.getElementById('tickerProvinces');
        this.tickerTotal = document.getElementById('tickerTotal');
        
        // Dados HSE Angola - Profissionais por província
        // Use dados reais do seu backend aqui
        this.hseData = [
            { province: 'Luanda', profession: 'Médico de Trabalho', count: 12, icon: 'fa-user-doctor' },
            { province: 'Malanje', profession: 'Psicólogo do Trabalho', count: 20, icon: 'fa-brain' },
            { province: 'Benguela', profession: 'Técnico de Segurança', count: 15, icon: 'fa-clipboard-list' },
            { province: 'Huambo', profession: 'Enfermeiro do Trabalho', count: 18, icon: 'fa-user-nurse' },
            { province: 'Cabinda', profession: 'Engenheiro Ambiental', count: 8, icon: 'fa-leaf' },
            { province: 'Lubango', profession: 'Higienista Ocupacional', count: 10, icon: 'fa-droplet' },
            { province: 'Namibe', profession: 'Bombeiro Industrial', count: 14, icon: 'fa-fire-extinguisher' },
            { province: 'Uíge', profession: 'Técnico de HSE', count: 22, icon: 'fa-helmet-safety' },
            { province: 'Cunene', profession: 'Médico do Trabalho', count: 7, icon: 'fa-stethoscope' },
            { province: 'Moxico', profession: 'Engenheiro de Segurança', count: 11, icon: 'fa-gears' },
            { province: 'Zaire', profession: 'Técnico de Higiene', count: 9, icon: 'fa-hand-holding-droplet' },
            { province: 'Cuando', profession: 'Brigadista', count: 16, icon: 'fa-truck' },
            { province: 'Bengo', profession: 'Socorrista', count: 13, icon: 'fa-truck-medical' },
            { province: 'Huíla', profession: 'Auditor HSE', count: 6, icon: 'fa-file-lines' },
            { province: 'Bié', profession: 'Químico Ambiental', count: 8, icon: 'fa-flask' }
        ];

        this.init();
    }

    init() {
        this.render();
        this.updateStats();
        
        // Atualiza dados a cada 30 segundos (opcional)
        setInterval(() => this.updateStats(), 30000);
    }

    render() {
        if (!this.tickerWrapper) return;
        
        // Cria DUAS listas idênticas para efeito infinito contínuo
        const list1 = this.createList();
        const list2 = this.createList();
        
        this.tickerWrapper.innerHTML = '';
        this.tickerWrapper.appendChild(list1);
        this.tickerWrapper.appendChild(list2);
    }

    createList() {
        const ul = document.createElement('ul');
        ul.className = 'ticker-list';
        
        this.hseData.forEach(item => {
            const li = document.createElement('li');
            
            li.innerHTML = `
                <span class="company">
                    <i class="fas ${item.icon}"></i>
                    ${item.province}
                </span>
                <span class="profession">
                    <i class="fas fa-briefcase"></i>
                    ${item.profession}:
                </span>
                <span class="value">
                    <i class="fas fa-user"></i>
                    ${item.count}
                </span>
                <span class="badge">
                    <i class="fas fa-shield"></i>
                    HSE
                </span>
            `;
            
            ul.appendChild(li);
        });
        
        return ul;
    }

    updateStats() {
        // Atualiza contadores do header
        if (this.tickerProvinces) {
            const uniqueProvinces = new Set(this.hseData.map(d => d.province)).size;
            this.tickerProvinces.textContent = uniqueProvinces;
        }
        
        if (this.tickerTotal) {
            const total = this.hseData.reduce((acc, curr) => acc + curr.count, 0);
            this.tickerTotal.textContent = total;
        }
    }
}

// Inicializa o ticker HSE
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('tickerWrapper')) {
        new HSEAngolaTicker();
    }
});