<?php
// pages/doc-template.php
global $lang, $pageAnchor, $routeParams;

function processMarkdownImages($html)
  {
  // Ищем паттерн: <p><img ... />{: .center}</p>
  $pattern = '/<p><img\s+([^>]+)\/>\{\:\s*\.center\}<\/p>/i';
  $replacement = '<p align="center"><img $1/></p>';

  return preg_replace($pattern, $replacement, $html);
  }


function fixRelativePathsInHtml($html, $baseUrl) {
  // Исправляем ссылки <a href="...">
  $html = preg_replace_callback(
        '/<a\s+([^>]*?)href="([^"]+)"([^>]*)>/i',
        function($matches) use ($baseUrl) {
            $href = $matches[2];
            // Если путь относительный (не начинается с /, #, http://, https://)
            if (!preg_match('/^(\\/|#|https?:|mailto:)/i', $href)) {
                $href = $baseUrl . $href;
            }
            return '<a ' . $matches[1] . 'href="' . $href . '"' . $matches[3] . '>';
        },
        $html
  );

  // Исправляем картинки <img src="...">
  $html = preg_replace_callback(
        '/<img\s+([^>]*?)src="([^"]+)"([^>]*)>/i',
        function($matches) use ($baseUrl) {
            $src = $matches[2];
            // Если путь относительный (не начинается с /, http://, https://, data:)
            if (!preg_match('/^(\\/|https?:|data:)/i', $src)) {
                $src = "/content" . $baseUrl . $src;
            }
            return '<img ' . $matches[1] . 'src="' . $src . '"' . $matches[3] . '>';
        },
        $html
  );

  return $html;
  }


// Разбираем путь
$parts = explode('/', $routeParams);
$book = $parts[0] ?? '';           // quickStart
$section = $parts[1] ?? 'intro';     // intro или toc по умолчанию

//Сначала пробуем загрузить md
$tocFile = "content/doc/{$book}/toc-{$lang}.md";
$sectionFile = "content/doc/{$book}/{$section}-{$lang}.md";
$tocContent = '';
$sectionContent = '';
$mainContent = '';
$navContent = '';

if( file_exists($tocFile) ) {
  require_once 'pages/Parsedown.php';  // единственный внешний файл в вашем проекте
  $Parsedown = new Parsedown();

  // Загружаем данные
  $tocContent = $Parsedown->text(file_exists($tocFile) ? file_get_contents($tocFile) : '');
  $sectionContent = $Parsedown->text(file_exists($sectionFile) ? file_get_contents($sectionFile) : '');
  $mainContent = processMarkdownImages($sectionContent);
  $navContent = $Parsedown->getToc();
  }
else {
  // Определяем файлы
  $tocFile = "content/doc/{$book}/toc-{$lang}.html";
  $sectionFile = "content/doc/{$book}/{$section}-{$lang}.html";

  // Если книга не существует - 404
  if( !file_exists($tocFile) ) {
    include 'pages/404.php';
    exit;
    }

  // Загружаем данные
  $tocContent = file_exists($tocFile) ? file_get_contents($tocFile) : '';
  $sectionContent = file_exists($sectionFile) ? file_get_contents($sectionFile) : '';

  // Извлекаем части секции
  $mainContent = extractSection($sectionContent, 'MAIN');
  $navContent = extractSection($sectionContent, 'NAV');
  }


$tocContent  = fixRelativePathsInHtml( $tocContent, "/doc/{$book}/" );
$mainContent = fixRelativePathsInHtml( $mainContent, "/doc/{$book}/" );

// Заголовок для книги
$bookTitle = extractIdent( $tocContent, 'Title' );

// Определяем навигацию вперед/назад
$prevSection = extractIdent( $sectionContent, 'Prev' );
$nextSection = extractIdent( $sectionContent, 'Next' );

// Хлебные крошки
$breadcrumb = $meta['breadcrumb'] ?? ['Документация', ucfirst($book)];
?>

<div class="doc-page">
    <!-- Левая колонка: Оглавление книги -->
    <aside class="doc-toc">
        <div class="toc-header">
            <h3><?= $bookTitle ?></h3>
            <button class="toc-toggle" onclick="toggleToc()">≡</button>
        </div>
        <div class="toc-content" id="toc-content">
            <?= $tocContent ?>
        </div>
    </aside>
    
    <!-- Центральная колонка: Содержимое секции -->
    <main class="doc-main" id="doc-main">
        <!-- Хлебные крошки -->
<!--        <nav class="doc-breadcrumb">
            <?php foreach ($breadcrumb as $i => $item): ?>
                <?php if ($i < count($breadcrumb) - 1): ?>
                    <a href="#"><?= htmlspecialchars($item) ?></a> /
                <?php else: ?>
                    <span><?= htmlspecialchars($item) ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav> -->
        
        <!-- Кнопки навигации (верх) -->
        <div class="doc-nav-top">
            <?php if ($prevSection): ?>
                <a href="/doc/<?= $book ?>/<?= $prevSection ?>" class="doc-nav-btn prev">
                    ← <?= $lang == 'ru' ? 'Предыдущая' : 'Previous' ?>
                </a>
            <?php endif; ?>
            
            <?php if ($nextSection): ?>
                <a href="/doc/<?= $book ?>/<?= $nextSection ?>" class="doc-nav-btn next">
                    <?= $lang == 'ru' ? 'Следующая' : 'Next' ?> →
                </a>
            <?php endif; ?>
        </div>
        
        <!-- Основное содержимое -->
        <article class="doc-content">
            <?= $mainContent ?>
        </article>
        
        <!-- Кнопки навигации (низ) -->
        <div class="doc-nav-bottom">
            <?php if ($prevSection): ?>
                <a href="/doc/<?= $book ?>/<?= $prevSection ?>" class="doc-nav-btn prev">
                    ← <?= $lang == 'ru' ? 'Предыдущая' : 'Previous' ?>
                </a>
            <?php endif; ?>
            
            <?php if ($nextSection): ?>
                <a href="/doc/<?= $book ?>/<?= $nextSection ?>" class="doc-nav-btn next">
                    <?= $lang == 'ru' ? 'Следующая' : 'Next' ?> →
                </a>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Правая колонка: Навигация по текущей секции -->
    <aside class="doc-section-nav">
        <div class="section-nav-header">
            <h4><?= $lang == 'ru' ? 'В этой секции' : 'In this section' ?></h4>
        </div>
        <nav class="section-nav-content" id="section-nav">
            <?= $navContent ?>
        </nav>
    </aside>
</div>

<style>
/* ========== СТИЛИ ДОКУМЕНТАЦИИ ========== */

.doc-page {
    display: grid;
    grid-template-columns: 280px 1fr 250px;
    gap: 20px;
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 20px;
    min-height: calc(100vh - 200px);
}

/* Левая колонка: Оглавление */
.doc-toc {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    position: sticky;
    top: 90px;
    max-height: calc(100vh - 120px);
}

.toc-header {
    background: #2c3e50;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.toc-header h3 {
    margin: 0;
    font-size: 16px;
    text-transform: capitalize;
}

.toc-toggle {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    display: none;
}

.toc-content {
    padding: 15px;
    overflow-y: auto;
    flex: 1;
}

.toc-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.toc-content li {
    margin-bottom: 8px;
}

.toc-content a {
    display: block;
    padding: 8px 12px;
    color: #34495e;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}

.toc-content a:hover {
    background: #f8f9fa;
    color: #3498db;
    border-left-color: #3498db;
}

.toc-content a.active {
    background: #e3f2fd;
    color: #1976d2;
    border-left-color: #1976d2;
    font-weight: bold;
}

.toc-section {
    display: block;
    padding: 10px 12px;
    font-weight: bold;
    color: #2c3e50;
    margin-top: 15px;
    border-bottom: 1px solid #eee;
}

.toc-description {
    font-size: 12px;
    color: #7f8c8d;
    margin: 2px 0 0 15px;
    font-style: italic;
}

/* Центральная колонка: Основное содержимое */
.doc-main {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
    overflow-y: auto;
    max-height: calc(100vh - 120px);
}

.doc-breadcrumb {
    //margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    color: #7f8c8d;
}

.doc-breadcrumb a {
    color: #3498db;
    text-decoration: none;
}

.doc-breadcrumb a:hover {
    text-decoration: underline;
}

.doc-content {
    line-height: 1.6;
}

.doc-content h1 {
    color: #2c3e50;
    border-bottom: 2px solid #3498db;
    padding-bottom: 10px;
    margin-bottom: 25px;
}

.doc-content h2 {
    color: #34495e;
    margin-top: 30px;
    margin-bottom: 15px;
    scroll-margin-top: 80px;
}

.doc-content h3 {
    color: #2c3e50;
    margin-top: 20px;
    margin-bottom: 10px;
}

/* Кнопки навигации */
.doc-nav-top,
.doc-nav-bottom {
    display: flex;
    //justify-content: space-between;
    //margin: 30px 0;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
}

.doc-nav-bottom {
    border-top: 1px solid #eee;
    border-bottom: none;
}

.doc-nav-btn {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: #f8f9fa;
    color: #34495e;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s;
}

/* Предыдущая кнопка прижимается влево */
.doc-nav-btn.prev {
    margin-right: auto;
}

/* Следующая кнопка прижимается вправо */
.doc-nav-btn.next {
    margin-left: auto;
}

/* Если есть обе кнопки, они будут по краям */
.doc-nav-btn.prev + .doc-nav-btn.next {
    margin-left: auto;
}

.doc-nav-btn:hover {
    background: #3498db;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}


.doc-nav-btn.prev::before {
   // content: '←';
    margin-right: 8px;
}

.doc-nav-btn.next::after {
 //   content: '→';
    margin-left: 8px;
}

/* Правая колонка: Навигация по секции */
.doc-section-nav {
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

.section-nav-header {
    background: #ecf0f1;
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
}

.section-nav-header h4 {
    margin: 0;
    color: #2c3e50;
    font-size: 15px;
}

.section-nav-content {
    padding: 15px;
    overflow-y: auto;
    flex: 1;
}

.section-nav-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.section-nav-content li {
    margin-bottom: 8px;
}

.section-nav-content a {
    display: block;
    padding: 8px 12px;
    color: #34495e;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}

.section-nav-content a:hover {
    background: #f8f9fa;
    color: #3498db;
}

.section-nav-content a.active {
    background: #e3f2fd;
    color: #1976d2;
    border-left-color: #1976d2;
    font-weight: bold;
}

/* Адаптивность */
@media (max-width: 1200px) {
    .doc-page {
        grid-template-columns: 250px 1fr;
    }
    
    .doc-section-nav {
        display: none;
    }
}

@media (max-width: 768px) {
    .doc-page {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .doc-toc {
        position: static;
        max-height: none;
    }
    
    .toc-toggle {
        display: block;
    }
    
    .toc-content.collapsed {
        display: none;
    }
}
</style>

<script>
// ========== ФУНКЦИОНАЛ ДОКУМЕНТАЦИИ ==========

document.addEventListener('DOMContentLoaded', function() {
    const tocContent = document.getElementById('toc-content');
    const mainContent = document.getElementById('doc-main');
    const sectionNav = document.getElementById('section-nav');
    
    // 1. АКТИВАЦИЯ ТЕКУЩЕЙ СЕКЦИИ В ОГЛАВЛЕНИИ
    function activateCurrentSection() {
        const currentPath = window.location.pathname;
        const links = tocContent.querySelectorAll('a[href]');
        
        links.forEach(link => {
            const href = link.getAttribute('href');
            // Проверяем совпадение пути
            if (currentPath.includes(href) || 
                (href.includes('#') && currentPath.includes(href.split('#')[0]))) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
    
    // 2. ПЛАВНАЯ ПРОКРУТКА К ЯКОРЮ (если есть)
    const urlHash = window.location.hash.substring(1);
    if (urlHash) {
        setTimeout(() => {
            const element = document.getElementById(urlHash);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }, 100);
    }
    
    // 3. АКТИВНАЯ НАВИГАЦИЯ В ПРАВОЙ КОЛОНКЕ
    if (sectionNav) {
        const sectionLinks = sectionNav.querySelectorAll('a[href^="#"]');
        
        function updateSectionNav() {
            const scrollTop = mainContent.scrollTop;
            const viewportHeight = mainContent.clientHeight;
            const triggerPosition = viewportHeight * 0.3;
            
            let activeLink = null;
            
            sectionLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href.startsWith('#')) {
                    const id = href.substring(1);
                    const element = document.getElementById(id);
                    
                    if (element) {
                        const rect = element.getBoundingClientRect();
                        const relativeTop = rect.top + mainContent.scrollTop;
                        
                        if (relativeTop <= scrollTop + triggerPosition) {
                            activeLink = link;
                        }
                    }
                }
            });
            
            // Обновляем активную ссылку
            sectionLinks.forEach(link => {
                if (link === activeLink) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }
        
        // Вешаем обработчики
        sectionLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('href').substring(1);
                const element = document.getElementById(id);
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
        
        mainContent.addEventListener('scroll', updateSectionNav);
        updateSectionNav(); // Инициализация
    }
    
    // 4. СВОРАЧИВАНИЕ/РАЗВОРАЧИВАНИЕ ОГЛАВЛЕНИЯ (для мобильных)
    window.toggleToc = function() {
        tocContent.classList.toggle('collapsed');
    };
    
    // 5. РАСКРЫТИЕ РОДИТЕЛЬСКИХ ЭЛЕМЕНТОВ ДЛЯ АКТИВНОЙ ССЫЛКИ
    function expandActiveParents() {
        const activeLink = tocContent.querySelector('a.active');
        if (activeLink) {
            // Поднимаемся вверх и раскрываем все родительские ul
            let parent = activeLink.parentElement;
            while (parent && parent !== tocContent) {
                if (parent.tagName === 'LI') {
                    const childUl = parent.querySelector('> ul');
                    if (childUl) {
                        childUl.style.display = 'block';
                    }
                }
                parent = parent.parentElement;
            }
        }
    }
    
    // Инициализация
    activateCurrentSection();
    expandActiveParents();
    
    // Горячие клавиши для навигации
    document.addEventListener('keydown', function(e) {
        // Стрелки влево/вправо для навигации между секциями
        if (e.altKey) {
            if (e.key === 'ArrowLeft' && <?= !empty($prevSection) ? 'true' : 'false' ?>) {
                window.location.href = '/doc/<?= $book ?>/<?= $prevSection ?>';
            }
            if (e.key === 'ArrowRight' && <?= !empty($nextSection) ? 'true' : 'false' ?>) {
                window.location.href = '/doc/<?= $book ?>/<?= $nextSection ?>';
            }
        }
    });
});
</script>