<?php
include 'common/mainMenu.php';

// ============================================
// 3. ФУНКЦИИ ДЛЯ РАБОТЫ С МЕНЮ
// ============================================

function getMenuText($data, $lang) {
    return $data[$lang] ?? $data['ru'] ?? '';
}

function isMenuItemActive($itemKey, $currentPage) {
    return strpos($currentPage, $itemKey) === 0;
}

// ============================================
// 4. ОТОБРАЖЕНИЕ ШАПКИ С МЕНЮ
// ============================================
?>

<header class="site-header">
    <nav class="main-nav">
        <!-- Логотип слева -->
        <div class="nav-left">
            <a href="/" class="logo">Circuit<span>CAD</span></a>
        </div>
        
        <!-- Основные пункты меню по центру -->
        <ul class="menu-list">
            <?php foreach ($menuItems as $key => $item): ?>
                <?php $isActive = isMenuItemActive($key, $currentPage); ?>
                <li class="menu-item <?= $isActive ? 'active' : '' ?>" data-menu-id="<?= $key ?>">
                    <a href="/<?= $key ?>" class="menu-link">
                        <?= getMenuText($item['name'], $lang) ?>
                    </a>
                    
                    <!-- Мегаменю (появляется при наведении) -->
                    <div class="megamenu" id="megamenu-<?= $key ?>">
                        <div class="menu-main-info">
                            <h2 class="menu-title"><?= getMenuText($item['name'], $lang) ?></h2>
                            <p class="menu-description">
                                <?= getMenuText($item['description'], $lang) ?>
                            </p>
                        </div>
                        
                        <?php if (!empty($item['submenu'])): ?>
                        <div class="menu-subitems">
                            <h3 class="submenu-title">
                                <?= $lang == 'ru' ? 'Разделы' : 'Sections' ?>
                            </h3>
                            
                            <?php foreach ($item['submenu'] as $subKey => $subItem): ?>
                            <div class="submenu-item">
                                <a href="<?= $subKey ?>" class="submenu-link">
                                    <?= getMenuText($subItem['name'], $lang) ?>
                                </a>
                                <p class="submenu-description">
                                    <?= getMenuText($subItem['description'], $lang) ?>
                                </p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <!-- Переключатель языка справа -->
        <div class="nav-right">
            <div class="language-switcher">
                <button class="current-lang" onclick="toggleLanguageDropdown()">
                    <?= $languages[$lang]['flag'] ?> <?= $languages[$lang]['name'] ?>
                </button>
                <div class="language-dropdown" id="languageDropdown">
                    <?php foreach ($languages as $code => $langData): ?>
                        <?php if ($code != $lang): ?>
                            <a href="?lang=<?= $code ?>" class="lang-option">
                                <?= $langData['flag'] ?> <?= $langData['name'] ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<style>
/* СТИЛИ МЕНЮ - ВСЁ В ОДНОМ МЕСТЕ */

/* Основной контейнер */
.site-header {
    background: #2c3e50;
    color: white;
    font-family: Arial, sans-serif;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* Навигационная панель */
.main-nav {
    display: flex;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 40px;
    height: 70px;
    position: relative;
}

/* Логотип слева (прижат к меню) */
.nav-left {
    margin-right: 50px; /* Отступ от меню */
}

.logo {
    font-size: 28px;
    font-weight: bold;
    color: white;
    text-decoration: none;
    display: inline-block;
    padding: 10px 0;
    transition: opacity 0.2s;
}

.logo span {
    color: #3498db;
}

.logo:hover {
    opacity: 0.8;
}

/* Основные пункты меню по центру */
.menu-list {
    display: flex;
    justify-content: center;
    align-items: center;
    list-style: none;
    margin: 0;
    padding: 0;
    flex: 1;
}

.menu-item {
    position: relative;
}

.menu-link {
    display: block;
    padding: 23px 25px;
    color: white;
    text-decoration: none;
    font-size: 16px;
    font-weight: 500;
    transition: background 0.2s;
    white-space: nowrap;
    height: 70px;
    box-sizing: border-box;
    line-height: 24px;
    position: relative;
}

.menu-link:hover,
.menu-item.active .menu-link {
    background: #3498db;
}

/* Индикатор активного пункта */
.menu-item.active .menu-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: #3498db;
}

/* Мегаменю */
.megamenu {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: 900px;
    background: white;
    color: #333;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    display: none;
    z-index: 1001;
    padding: 30px;
    border-radius: 0 0 8px 8px;
    border-top: 3px solid #3498db;
    opacity: 0;
    transition: opacity 0.2s;
}

.megamenu.active {
    display: flex;
    opacity: 1;
}

/* Основная информация пункта меню */
.menu-main-info {
    flex: 1;
    padding-right: 40px;
    border-right: 1px solid #ecf0f1;
}

.menu-title {
    font-size: 26px;
    font-weight: bold;
    color: #2c3e50;
    margin: 0 0 15px 0;
}

.menu-description {
    font-size: 15px;
    line-height: 1.5;
    color: #7f8c8d;
    margin: 0;
}

/* Подменю */
.menu-subitems {
    flex: 1;
    padding-left: 40px;
}

.submenu-title {
    font-size: 18px;
    font-weight: bold;
    color: #2c3e50;
    margin: 0 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid #3498db;
}

.submenu-item {
    margin-bottom: 18px;
}

.submenu-link {
    display: block;
    color: #3498db;
    text-decoration: none;
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 4px;
    transition: color 0.2s;
}

.submenu-link:hover {
    color: #2980b9;
    text-decoration: underline;
}

.submenu-description {
    font-size: 13px;
    color: #666;
    line-height: 1.4;
    margin: 0;
    font-style: italic;
}

/* Переключатель языка справа (прижат к меню) */
.nav-right {
    margin-left: 50px; /* Отступ от меню */
}

.language-switcher {
    position: relative;
}

.current-lang {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    min-width: 120px;
    justify-content: center;
}

.current-lang:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.4);
}

.language-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 4px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    display: none;
    min-width: 180px;
    z-index: 1002;
    margin-top: 5px;
    overflow: hidden;
}

.language-dropdown.show {
    display: block;
}

.lang-option {
    display: block;
    padding: 12px 15px;
    color: #333;
    text-decoration: none;
    font-size: 14px;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s;
}

.lang-option:last-child {
    border-bottom: none;
}

.lang-option:hover {
    background: #f5f5f5;
    color: #3498db;
}

/* Слой для закрытия выпадающих меню */
.close-layer {
    position: fixed;
    top: 70px; /* Высота меню */
    left: 0;
    right: 0;
    bottom: 0;
    background: transparent;
    z-index: 999;
    display: none;
}

.close-layer.active {
    display: block;
}
</style>

<script>
// Управление выпадающими меню
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('.menu-item');
    const languageBtn = document.querySelector('.current-lang');
    const languageDropdown = document.getElementById('languageDropdown');
    const closeLayer = document.createElement('div');
    closeLayer.className = 'close-layer';
    document.body.appendChild(closeLayer);
    
    let activeMegamenu = null;
    let megamenuTimeout = null;
    
    // Функция для скрытия всех мегаменю
    function hideAllMegamenus() {
        document.querySelectorAll('.megamenu').forEach(megamenu => {
            megamenu.classList.remove('active');
        });
        activeMegamenu = null;
    }
    
    // Функция для скрытия выпадающего списка языков
    function hideLanguageDropdown() {
        languageDropdown.classList.remove('show');
    }
    
    // Обработка мегаменю
    menuItems.forEach(item => {
        const megamenu = item.querySelector('.megamenu');
        if (!megamenu) return;
        
        // При наведении на пункт меню
        item.addEventListener('mouseenter', function() {
            clearTimeout(megamenuTimeout);
            hideLanguageDropdown();
            
            // Скрыть предыдущее активное мегаменю
            if (activeMegamenu && activeMegamenu !== megamenu) {
                activeMegamenu.classList.remove('active');
            }
            
            // Показать текущее мегаменю
            megamenu.classList.add('active');
            activeMegamenu = megamenu;
        });
        
        // При уходе с пункта меню
        item.addEventListener('mouseleave', function(e) {
            // Проверяем, перешел ли курсор на мегаменю
            const relatedTarget = e.relatedTarget;
            const isMovingToMegamenu = megamenu.contains(relatedTarget);
            
            if (!isMovingToMegamenu) {
                megamenuTimeout = setTimeout(() => {
                    if (activeMegamenu === megamenu) {
                        megamenu.classList.remove('active');
                        activeMegamenu = null;
                    }
                }, 150);
            }
        });
        
        // При наведении на само мегаменю
        megamenu.addEventListener('mouseenter', function() {
            clearTimeout(megamenuTimeout);
        });
        
        // При уходе с мегаменю
        megamenu.addEventListener('mouseleave', function(e) {
            // Проверяем, перешел ли курсор на пункт меню
            const relatedTarget = e.relatedTarget;
            const isMovingToMenuItem = item.contains(relatedTarget);
            
            if (!isMovingToMenuItem) {
                megamenuTimeout = setTimeout(() => {
                    if (activeMegamenu === megamenu) {
                        megamenu.classList.remove('active');
                        activeMegamenu = null;
                    }
                }, 100);
            }
        });
    });
    
    // Переключатель языка
    function toggleLanguageDropdown() {
        const isVisible = languageDropdown.classList.contains('show');
        
        // Скрыть все мегаменю
        hideAllMegamenus();
        
        if (isVisible) {
            hideLanguageDropdown();
            closeLayer.classList.remove('active');
        } else {
            languageDropdown.classList.add('show');
            closeLayer.classList.add('active');
        }
    }
    
    // Клик вне меню закрывает все
    closeLayer.addEventListener('click', function() {
        hideAllMegamenus();
        hideLanguageDropdown();
        closeLayer.classList.remove('active');
    });
    
    // Клик на опцию языка
    languageDropdown.addEventListener('click', function(e) {
        if (e.target.classList.contains('lang-option')) {
            closeLayer.classList.remove('active');
        }
    });
    
    // Закрытие при клике на документ (для надежности)
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.language-switcher') && 
            !e.target.closest('.menu-item') && 
            !e.target.closest('.megamenu')) {
            hideAllMegamenus();
            hideLanguageDropdown();
            closeLayer.classList.remove('active');
        }
    });
    
    // Глобальная функция
    window.toggleLanguageDropdown = toggleLanguageDropdown;
    window.hideAllMegamenus = hideAllMegamenus;
});
</script>