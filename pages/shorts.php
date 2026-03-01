<?php
// pages/shorts.php - базовый шаблон для коротких страниц

$content = loadPageContent($pageName);

// Извлекаем секции
$mainContent = extractSection($content, 'MAIN');
$navContent = extractSection($content, 'NAV');
$asideContent = extractSection($content, 'ASIDE'); // Для дополнительного контента справа
?>

<div class="page-template">
    <!-- Основной контент -->
    <main class="page-main" id="page-main">
        <?= !empty($mainContent) ? $mainContent : 'Контент страницы' ?>
    </main>
    
    <!-- Боковая панель навигации -->
    <aside class="page-sidebar">
        <?php if (!empty($navContent)): ?>
        <nav class="page-nav" id="page-nav">
            <div class="nav-header">
                <h3><?= $lang == 'ru' ? 'Навигация' : 'Navigation' ?></h3>
            </div>
            <div class="nav-content">
                <?= $navContent ?>
            </div>
        </nav>
        <?php endif; ?>
        
        <?php if (!empty($asideContent)): ?>
        <div class="page-aside">
            <?= $asideContent ?>
        </div>
        <?php endif; ?>
    </aside>
</div>

<style>
/* ========== ШАБЛОН СТРАНИЦЫ ========== */

.page-template {
    display: flex;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    gap: 40px;
    min-height: 600px;
}

/* Основной контент */
.page-main {
    flex: 1;
    min-width: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
    overflow-y: auto;
    max-height: calc(100vh - 200px);
}

/* Боковая панель */
.page-sidebar {
    width: 300px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Навигация по странице */
.page-nav {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    position: sticky;
    top: 90px;
    max-height: calc(100vh - 120px);
    display: flex;
    flex-direction: column;
}

.nav-header {
    background: #3498db;
    color: white;
    padding: 15px 20px;
}

.nav-header h3 {
    margin: 0;
    font-size: 18px;
}

.nav-content {
    padding: 15px 0;
    overflow-y: auto;
    flex: 1;
}

/* Стили для навигационных ссылок */
.nav-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-content li {
    margin: 0;
}

.nav-content a {
    display: block;
    padding: 10px 20px;
    color: #34495e;
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: all 0.2s;
    position: relative;
}

.nav-content a:hover {
    background: #f8f9fa;
    color: #3498db;
    border-left-color: #3498db;
}

.nav-content a.active {
    background: #ecf0f1;
    color: #2c3e50;
    border-left-color: #2c3e50;
    font-weight: bold;
}

/* Индикатор видимой секции */
.nav-content a.active::before {
    content: '▶';
    position: absolute;
    left: 5px;
    color: #e74c3c;
}

/* Дополнительный контент в сайдбаре */
.page-aside {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 20px;
}

/* Стили для заголовков в основном контенте */
.page-main h2 {
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
    margin-top: 30px;
    margin-bottom: 20px;
    scroll-margin-top: 80px; /* Для плавной прокрутки */
}

.page-main h3 {
    color: #34495e;
    margin-top: 25px;
    margin-bottom: 15px;
    scroll-margin-top: 80px;
}

.page-main h4 {
    color: #2c3e50;
    margin-top: 20px;
    margin-bottom: 10px;
}

/* Стили для таблиц (для страницы загрузок) */
.download-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.download-table th {
    background: #f8f9fa;
    color: #2c3e50;
    font-weight: bold;
    text-align: left;
    padding: 12px 15px;
    border-bottom: 2px solid #3498db;
}

.download-table td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
}

.download-table tr:hover {
    background: #f8f9fa;
}

.download-btn {
    display: inline-block;
    background: #27ae60;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s;
}

.download-btn:hover {
    background: #219653;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.download-btn.secondary {
    background: #3498db;
}

.download-btn.secondary:hover {
    background: #2980b9;
}

/* Информационные блоки */
.info-box {
    background: #e8f4fc;
    border-left: 4px solid #3498db;
    padding: 15px;
    margin: 20px 0;
    border-radius: 0 4px 4px 0;
}

.warning-box {
    background: #fef9e7;
    border-left: 4px solid #f39c12;
    padding: 15px;
    margin: 20px 0;
    border-radius: 0 4px 4px 0;
}

/* Адаптивность */
@media (max-width: 1024px) {
    .page-template {
        flex-direction: column;
    }
    
    .page-sidebar {
        width: 100%;
        order: -1;
    }
    
    .page-nav {
        position: static;
        max-height: none;
    }
}
</style>

<script>
// ========== АКТИВНАЯ НАВИГАЦИЯ ПО СТРАНИЦЕ ==========

document.addEventListener('DOMContentLoaded', function() {
    const mainContent = document.getElementById('page-main');
    const navContainer = document.getElementById('page-nav');
    
    if (!mainContent || !navContainer) return;
    
    const navLinks = navContainer.querySelectorAll('a[href^="#"]');
    const sections = [];
    
    // 1. СОБИРАЕМ ВСЕ СЕКЦИИ С ЗАГОЛОВКАМИ
    const headers = mainContent.querySelectorAll('h2, h3, h4');
    headers.forEach(header => {
        // Создаем id если его нет
        if (!header.id) {
            header.id = 'section-' + header.textContent
                .toLowerCase()
                .replace(/[^\w\u0400-\u04FF]+/g, '-')
                .replace(/^-|-$/g, '');
        }
        
        sections.push({
            id: header.id,
            element: header,
            top: 0
        });
    });
    
    // 2. ФУНКЦИЯ ОБНОВЛЕНИЯ АКТИВНОЙ СЕКЦИИ
    function updateActiveSection() {
        const scrollTop = mainContent.scrollTop || document.documentElement.scrollTop;
        const viewportHeight = mainContent.clientHeight;
        const triggerPosition = viewportHeight * 0.3; // 30% от верха
        
        let activeSection = null;
        
        // Находим секцию, которая находится в области видимости
        sections.forEach(section => {
            const rect = section.element.getBoundingClientRect();
            const relativeTop = rect.top + mainContent.scrollTop;
            
            if (relativeTop <= scrollTop + triggerPosition) {
                activeSection = section;
            }
        });
        
        // Обновляем активную ссылку в навигации
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === '#' + (activeSection ? activeSection.id : '')) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
        
        // Если ни одна секция не активна, делаем активной первую
        if (!activeSection && navLinks.length > 0) {
            navLinks[0].classList.add('active');
        }
    }
    
    // 3. ПЛАВНАЯ ПРОКРУТКА ПРИ КЛИКЕ НА ССЫЛКУ
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                // Плавная прокрутка к секции
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Обновляем URL без перезагрузки
                history.pushState(null, null, '#' + targetId);
            }
        });
    });
    
    // 4. СЛЕДИМ ЗА ПРОКРУТКОЙ
    mainContent.addEventListener('scroll', updateActiveSection);
    
    // 5. ОБРАБОТКА ХЭШЕЙ ПРИ ЗАГРУЗКЕ СТРАНИЦЫ
    window.addEventListener('load', function() {
        const hash = window.location.hash.substring(1);
        if (hash) {
            const targetElement = document.getElementById(hash);
            if (targetElement) {
                setTimeout(() => {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }, 100);
            }
        }
        updateActiveSection(); // Инициализация
    });
    
    // 6. ОБНОВЛЕНИЕ ПРИ ИЗМЕНЕНИИ РАЗМЕРА ОКНА
    window.addEventListener('resize', updateActiveSection);
});
</script>