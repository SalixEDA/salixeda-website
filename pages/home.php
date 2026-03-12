<?php
// pages/home.php

$content = loadPageContent( 'home' );

// Извлекаем секции
$galleryContent = extractSection($content, 'GALLERY');
$featuresContent = extractSection($content, 'FEATURES');
$sidebarContent = extractSection($content, 'SIDEBAR');

// Настройки галереи (можно вынести в конфиг)
$gallerySettings = [
    'interval' => 5000, // 5 секунд между сменой слайдов
    'transition' => 800, // длительность анимации в мс
    'autoPlay' => true
];
?>

<!-- ГАЛЕРЕЯ - ИСПРАВЛЕННАЯ ВЕРСИЯ -->
<section class="gallery-section">
    <div class="gallery-container" 
         data-interval="<?= $gallerySettings['interval'] ?>" 
         data-transition="<?= $gallerySettings['transition'] ?>"
         data-autoplay="<?= $gallerySettings['autoPlay'] ? 'true' : 'false' ?>">
        
        <div class="gallery-slides">
            <?php if (!empty($galleryContent)): ?>
                <?= $galleryContent ?>
            <?php else: ?>
                <!-- Заглушка если нет контента -->
                <div class="slide active">
                    <img src="/images/slides/default.jpg" alt="CircuitCAD">
                    <div class="caption">
                        <h3>CircuitCAD</h3>
                        <p><?= $lang == 'ru' ? 'Профессиональная САПР для схемотехники' : 'Professional CAD for circuit design' ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Навигация галереи -->
        <div class="gallery-nav">
            <button class="gallery-prev" aria-label="<?= $lang == 'ru' ? 'Предыдущий слайд' : 'Previous slide' ?>">‹</button>
            <div class="gallery-dots">
                <!-- Точки будут добавлены JavaScript -->
            </div>
            <button class="gallery-next" aria-label="<?= $lang == 'ru' ? 'Следующий слайд' : 'Next slide' ?>">›</button>
        </div>
        
        <!-- Ссылка "Подробнее" -->
        <div class="gallery-more">
            <a href="#" class="more-link" style="display: none;">
                <?= $lang == 'ru' ? 'Подробнее →' : 'Learn more →' ?>
            </a>
        </div>
    </div>
</section>

<!-- ОСНОВНОЕ СОДЕРЖИМОЕ -->
<div class="home-content">
    <main class="main-features">
        <?= !empty($featuresContent) ? $featuresContent : '' ?>
        
        <div class="all-features-link">
            <a href="/about/features" class="btn btn-large">
                <?= $lang == 'ru' ? 'Все возможности' : 'All features' ?>
            </a>
        </div>
    </main>
    
    <aside class="home-sidebar">
        <?= !empty($sidebarContent) ? $sidebarContent : '' ?>
    </aside>
</div>

<style>
/* ОБНОВЛЕННЫЕ СТИЛИ ГАЛЕРЕИ */

/* Галерея - фикс первоначального отображения */
.gallery-section {
    background: #1a252f;
    margin: 0 0 40px 0;
    position: relative;
    overflow: hidden;
    height: 500px;
}

.gallery-container {
    position: relative;
    height: 100%;
    max-width: 1400px;
    margin: 0 auto;
}

/* Слайды - ВАЖНО: первый слайд видим сразу */
.gallery-slides {
    position: relative;
    height: 100%;
    width: 100%;
}

.gallery-slides .slide {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    transition: opacity 0.8s ease;
    z-index: 1;
}


/* Когда JS отработает, он добавит класс .active */
.gallery-slides .slide.active {
    opacity: 1 !important;
    z-index: 3 !important;
}

.gallery-slides .slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.7);
}

.gallery-slides .caption {
    position: absolute;
    bottom: 80px;
    left: 0;
    right: 0;
    text-align: center;
    color: white;
    padding: 0 20px;
    z-index: 4;
    max-width: 800px;
    margin: 0 auto;
}

.gallery-slides .caption h3 {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 15px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.5);
}

.gallery-slides .caption p {
    font-size: 20px;
    opacity: 0.9;
    text-shadow: 0 1px 5px rgba(0,0,0,0.5);
}

/* Навигация галереи */
.gallery-nav {
    position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    z-index: 10;
}

.gallery-prev,
.gallery-next {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    font-size: 24px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 11;
}

.gallery-prev:hover,
.gallery-next:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.1);
}

.gallery-dots {
    display: flex;
    gap: 10px;
    z-index: 11;
}

.gallery-dots .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    cursor: pointer;
    transition: all 0.3s;
}

.gallery-dots .dot.active {
    background: #3498db;
    transform: scale(1.2);
}

.gallery-dots .dot:hover {
    background: rgba(255,255,255,0.5);
}

/* Ссылка "Подробнее" */
.gallery-more {
    position: absolute;
    bottom: 20px;
    right: 20px;
    z-index: 11;
}

.gallery-more .more-link {
    background: rgba(52, 152, 219, 0.9);
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s;
    display: inline-block;
}

.gallery-more .more-link:hover {
    background: rgba(41, 128, 185, 0.9);
    transform: translateY(-2px);
}

/* Анимация слайдов */
@keyframes slideInRight {
    from { 
        transform: translateX(100%); 
        opacity: 0; 
    }
    to { 
        transform: translateX(0); 
        opacity: 1; 
    }
}

.slide.active.slide-in {
    animation: slideInRight 0.8s ease;
}

/* Остальные стили (основной контент) такие же как были */
.home-content {
    display: flex;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    gap: 40px;
}

.main-features {
    flex: 3;
    min-width: 0;
}

.home-sidebar {
    flex: 1;
    min-width: 300px;
}

.zigzag {
    overflow: hidden;  /* чтобы блок не схлопывался */
    margin-bottom: 40px;
}
.zigzag-img {
    float: left;
    width: 200px;      /* нужный размер */
    margin-right: 30px;
    margin-bottom: 20px;
}
.zigzag.right .zigzag-img {
    float: right;
    margin-right: 0;
    margin-left: 30px;
}
.zigzag-text {
    overflow: hidden;  /* чтобы текст не обтекал лишнего */
}

/* ... остальные стили из предыдущего ответа ... */
</style>

<script>
// ИСПРАВЛЕННЫЙ КОД ГАЛЕРЕИ
document.addEventListener('DOMContentLoaded', function() {
    const gallery = document.querySelector('.gallery-container');
    if (!gallery) return;
    
    const slides = gallery.querySelectorAll('.slide');
    if (slides.length === 0) return;
    
    const dotsContainer = gallery.querySelector('.gallery-dots');
    const prevBtn = gallery.querySelector('.gallery-prev');
    const nextBtn = gallery.querySelector('.gallery-next');
    const moreLink = gallery.querySelector('.more-link');
    
    // Настройки
    const interval = parseInt(gallery.dataset.interval) || 5000;
    const transition = parseInt(gallery.dataset.transition) || 800;
    const autoPlay = gallery.dataset.autoplay === 'true';
    
    let currentSlide = 0;
    let slideInterval;
    
    // ИНИЦИАЛИЗАЦИЯ: делаем первый слайд активным
    slides[0].classList.add('active');
    
    // Создаем точки навигации
    slides.forEach((slide, index) => {
        const dot = document.createElement('span');
        dot.className = 'dot';
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });
    
    const dots = dotsContainer.querySelectorAll('.dot');
    
    // Функция перехода к слайду
    function goToSlide(index) {
        // Скрываем текущий слайд
        slides[currentSlide].classList.remove('active');
        dots[currentSlide].classList.remove('active');
        
        // Показываем новый слайд с анимацией
        currentSlide = index;
        slides[currentSlide].classList.add('active');
        slides[currentSlide].classList.add('slide-in');
        dots[currentSlide].classList.add('active');
        
        // Убираем класс анимации после завершения
        setTimeout(() => {
            slides[currentSlide].classList.remove('slide-in');
        }, transition);
        
        // Обновляем ссылку "Подробнее"
        updateMoreLink();
        
        // Сбрасываем автоплеер
        resetInterval();
    }
    
    // Обновление ссылки "Подробнее"
    function updateMoreLink() {
        const currentSlideEl = slides[currentSlide];
        const ref = currentSlideEl.dataset.ref;
        
        if (ref) {
            moreLink.href = '/' + ref;
            moreLink.style.display = 'inline-block';
        } else {
            moreLink.style.display = 'none';
        }
    }
    
    // Следующий слайд
    function nextSlide() {
        let next = currentSlide + 1;
        if (next >= slides.length) next = 0;
        goToSlide(next);
    }
    
    // Предыдущий слайд
    function prevSlide() {
        let prev = currentSlide - 1;
        if (prev < 0) prev = slides.length - 1;
        goToSlide(prev);
    }
    
    // Сброс интервала автоплеера
    function resetInterval() {
        if (autoPlay) {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, interval);
        }
    }
    
    // Обработчики кнопок (ПРОВЕРЬТЕ ЧТО ЭЛЕМЕНТЫ СУЩЕСТВУЮТ!)
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetInterval();
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetInterval();
        });
    }
    
    // Клавиатурная навигация
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            prevSlide();
            resetInterval();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            resetInterval();
        }
    });
    
    // Пауза при наведении
    gallery.addEventListener('mouseenter', () => {
        if (autoPlay && slideInterval) {
            clearInterval(slideInterval);
        }
    });
    
    gallery.addEventListener('mouseleave', () => {
        if (autoPlay) {
            resetInterval();
        }
    });
    
    // Инициализация
    updateMoreLink();
    if (autoPlay) {
        slideInterval = setInterval(nextSlide, interval);
    }
    
    // Убираем анимацию с первого слайда после загрузки
    setTimeout(() => {
        slides[0].classList.remove('slide-in');
    }, 100);
});

</script>