<?php
// pages/articleSingle.php - С ПРАВИЛЬНЫМИ СТИЛЯМИ

global $routeParams, $lang;

// ID записи из URL
$postId = $routeParams; //$params[0] ?? '';

// Загружаем список всех записей
$entriesFile = 'content/articles/entries.json';
$allEntries = file_exists($entriesFile) ? json_decode(file_get_contents($entriesFile), true) : [];
$singAbout  = $allEntries['singAbout'];
$singDate   = $allEntries['singDate'];
$singAuthor = $allEntries['singAuthor'];
$singRTime  = $allEntries['singRTime'];
$singTags   = $allEntries['singTags'];
$singBack   = $allEntries['singBack'];
$singPrev   = $allEntries['singPrev'];
$singNext   = $allEntries['singNext'];
$singContents = $allEntries['singContents'];

$allEntries = $allEntries['entries'] ?? [];

// Ищем текущую запись
$currentEntry = null;
$currentIndex = -1;
$prevEntry = null;
$nextEntry = null;

foreach ($allEntries as $index => $entry) {
    if ($entry['id'] === $postId) {
        $currentEntry = $entry;
        $currentIndex = $index;
        
        // Находим соседние записи
        if (isset($allEntries[$index - 1])) {
            $prevEntry = $allEntries[$index - 1];
        }
        if (isset($allEntries[$index + 1])) {
            $nextEntry = $allEntries[$index + 1];
        }
        break;
    }
}

// Если запись не найдена - 404
if (!$currentEntry) {
    echo "<!-- Record not found -->\n";
    include 'pages/404.php';
    http_response_code(404);
    exit;
}

// Загружаем содержимое записи
$contentFile = "content/articles/{$postId}-{$lang}.html";
if (!file_exists($contentFile)) {
    include 'pages/404.php';
    http_response_code(404);
    exit;
}

$content = file_get_contents($contentFile);

// Извлекаем части контента
$mainContent = extractSection($content, 'MAIN');
$navContent = extractSection($content, 'NAV');

// Подготавливаем данные из текущей записи
$postTitle = $currentEntry['title'][$lang] ?? $currentEntry['title']['ru'] ?? '';
$postDate = $currentEntry['date'] ?? '';
$postAuthor = $currentEntry['author'] ?? '';
$postTags = $currentEntry['tags'] ?? [];
$readTime = $currentEntry['read_time'] ?? '';

// Если в содержимом есть заголовок, используем его вместо заголовка из entries.json
if (preg_match('/<h1[^>]*>(.*?)<\/h1>/', $mainContent, $matches)) {
    $postTitle = strip_tags($matches[1]);
}
?>

<!-- СОБСТВЕННЫЙ ШАБЛОН ДЛЯ СТРАНИЦЫ БЛОГА, НЕ ДОКУМЕНТАЦИИ -->
<div class="blog-single-page">
    <!-- Левая колонка: Информация о записи -->
    <aside class="blog-single-sidebar">
        <div class="sidebar-header">
            <h3><?= $singAbout[$lang] ?></h3>
        </div>
        <div class="sidebar-content">
            <div class="post-info">
                <?php if (!empty($postDate)): ?>
                <div class="info-item">
                    <strong><?= $singDate[$lang] ?></strong>
                    <span><?= date('d.m.Y', strtotime($postDate)) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($postAuthor)): ?>
                <div class="info-item">
                    <strong><?= $singAuthor[$lang] ?></strong>
                    <span><?= htmlspecialchars($postAuthor) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($readTime)): ?>
                <div class="info-item">
                    <strong><?= $singRTime[$lang] ?></strong>
                    <span><?= htmlspecialchars($readTime) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($postTags)): ?>
                <div class="info-item">
                    <strong><?= $singTags[$lang] ?></strong>
                    <div class="post-tags">
                        <?php foreach ($postTags as $tag): ?>
                            <a href="/articles?tag=<?= urlencode($tag) ?>" class="tag">
                                <?= htmlspecialchars($tag) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Кнопка возврата к списку -->
            <a href="/articles" class="back-to-list" id="back-to-list">
                ← <?= $singBack[$lang] ?>
            </a>
            
            <!-- Мини-навигация -->
            <?php if ($prevEntry || $nextEntry): ?>
            <div class="mini-navigation">
                <?php if ($prevEntry): ?>
                <a href="/articles/<?= htmlspecialchars($prevEntry['id']) ?>" class="nav-mini prev">
                    ← <?= $singPrev[$lang] ?>
                </a>
                <?php endif; ?>
                
                <?php if ($nextEntry): ?>
                <a href="/articles/<?= htmlspecialchars($nextEntry['id']) ?>" class="nav-mini next">
                    <?= $singNext[$lang] ?> →
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </aside>
    
    <!-- Центральная колонка: Содержимое записи -->
    <main class="blog-single-main">
        <!-- Заголовок записи -->
        <header class="post-header">
            <h1><?= htmlspecialchars($postTitle) ?></h1>
            
            <div class="post-meta">
                <?php if (!empty($postDate)): ?>
                <time datetime="<?= htmlspecialchars($postDate) ?>">
                    <?= date('d.m.Y', strtotime($postDate)) ?>
                </time>
                <?php endif; ?>
                
                <?php if (!empty($readTime)): ?>
                <span class="read-time">• <?= htmlspecialchars($readTime) ?></span>
                <?php endif; ?>
                
                <?php if (!empty($postAuthor)): ?>
                <span class="author">• <?= htmlspecialchars($postAuthor) ?></span>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($postTags)): ?>
            <div class="post-tags-main">
                <?php foreach ($postTags as $tag): ?>
                    <a href="/articles?tag=<?= urlencode($tag) ?>" class="tag">
                        <?= htmlspecialchars($tag) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </header>
        
        <!-- Основное содержимое -->
        <article class="post-content">
            <?= $mainContent ?>
        </article>
        
        <!-- Навигация между записями -->
        <?php if ($prevEntry || $nextEntry): ?>
        <div class="post-navigation">
            <?php if ($prevEntry): ?>
            <a href="/articles/<?= htmlspecialchars($prevEntry['id']) ?>" class="nav-btn prev">
                <span class="nav-label"><?= $singPrev[$lang] ?></span>
                <span class="nav-title"><?= htmlspecialchars($prevEntry['title'][$lang] ?? $prevEntry['title']['ru'] ?? '') ?></span>
            </a>
            <?php endif; ?>
            
            <?php if ($nextEntry): ?>
            <a href="/articles/<?= htmlspecialchars($nextEntry['id']) ?>" class="nav-btn next">
                <span class="nav-label"><?= $singNext[$lang] ?></span>
                <span class="nav-title"><?= htmlspecialchars($nextEntry['title'][$lang] ?? $nextEntry['title']['ru'] ?? '') ?></span>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Кнопка возврата к списку -->
        <div class="back-to-list-bottom">
            <a href="/articles" class="back-btn">
                ← <?= $singBack[$lang] ?>
            </a>
        </div>
    </main>
    
    <!-- Правая колонка: Навигация по записи -->
    <aside class="post-toc">
        <div class="toc-header">
            <h4><?= $singContents[$lang] ?></h4>
        </div>
        <nav class="toc-content">
            <?= $navContent ?>
        </nav>
    </aside>
</div>

<style>
/* ========== ОСНОВНЫЕ СТИЛИ ДЛЯ СТРАНИЦЫ БЛОГА ========== */

.blog-single-page {
    display: grid;
    grid-template-columns: 280px 1fr 250px;
    gap: 30px;
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 20px;
    min-height: calc(100vh - 200px);
}

/* Левая колонка */
.blog-single-sidebar {
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

.sidebar-header {
    background: #3498db;
    color: white;
    padding: 15px 20px;
}

.sidebar-header h3 {
    margin: 0;
    font-size: 16px;
}

.sidebar-content {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
}

/* Информация о записи */
.info-item {
    margin-bottom: 20px;
}

.info-item strong {
    display: block;
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 14px;
}

.info-item span {
    color: #34495e;
    font-size: 15px;
}

.post-tags {
    margin-top: 5px;
}

.tag {
    display: inline-block;
    background: #ecf0f1;
    color: #7f8c8d;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    margin-right: 5px;
    margin-bottom: 5px;
    text-decoration: none;
}

.tag:hover {
    background: #3498db;
    color: white;
}

/* Кнопка возврата */
.back-to-list {
    display: block;
    margin-top: 20px;
    padding: 10px 15px;
    background: #f8f9fa;
    color: #3498db;
    text-decoration: none;
    text-align: center;
    border-radius: 4px;
    font-weight: bold;
    transition: all 0.2s;
}

.back-to-list:hover {
    background: #e3f2fd;
}

/* Мини-навигация */
.mini-navigation {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.nav-mini {
    padding: 8px 12px;
    background: #f8f9fa;
    color: #3498db;
    text-decoration: none;
    border-radius: 4px;
    font-size: 13px;
    font-weight: bold;
}

.nav-mini:hover {
    background: #e3f2fd;
}

/* Центральная колонка */
.blog-single-main {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 30px;
    overflow-y: auto;
    max-height: calc(100vh - 120px);
}

/* Заголовок записи */
.post-header {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.post-header h1 {
    color: #2c3e50;
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 32px;
}

.post-meta {
    color: #7f8c8d;
    font-size: 15px;
    margin-bottom: 15px;
}

.post-tags-main {
    margin-top: 15px;
}

.post-tags-main .tag {
    font-size: 13px;
}

/* Содержимое записи */
.post-content {
    line-height: 1.7;
    color: #34495e;
}

.post-content h2 {
    color: #2c3e50;
    margin-top: 30px;
    margin-bottom: 15px;
    font-size: 24px;
}

.post-content h3 {
    color: #34495e;
    margin-top: 25px;
    margin-bottom: 12px;
    font-size: 20px;
}

.post-content p {
    margin-bottom: 20px;
}

.post-content ul, .post-content ol {
    margin-bottom: 20px;
    padding-left: 20px;
}

.post-content li {
    margin-bottom: 8px;
}

/* Навигация между записями */
.post-navigation {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 40px 0;
    padding: 30px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.nav-btn {
    display: block;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    text-decoration: none;
    color: #34495e;
    transition: all 0.3s;
}

.nav-btn:hover {
    background: #e3f2fd;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.nav-btn.prev {
    text-align: left;
}

.nav-btn.next {
    text-align: right;
}

.nav-label {
    display: block;
    color: #7f8c8d;
    font-size: 14px;
    margin-bottom: 5px;
}

.nav-title {
    display: block;
    color: #3498db;
    font-weight: bold;
    font-size: 16px;
}

/* Кнопка возврата внизу */
.back-to-list-bottom {
    text-align: center;
    margin-top: 30px;
}

.back-btn {
    display: inline-block;
    padding: 12px 30px;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: bold;
    transition: all 0.3s;
}

.back-btn:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

/* Правая колонка: содержание */
.post-toc {
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
    background: #ecf0f1;
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
}

.toc-header h4 {
    margin: 0;
    color: #2c3e50;
    font-size: 15px;
}

.toc-content {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
}

.toc-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.toc-content li {
    margin-bottom: 10px;
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
}

.toc-content a.active {
    background: #e3f2fd;
    color: #1976d2;
    border-left-color: #1976d2;
    font-weight: bold;
}

/* Предупреждение для разработчиков */
.dev-note {
    background: #f0f7ff;
    border-left: 4px solid #2c7da0;
    padding: 16px 20px;
    margin: 20px 0 30px 0;
    border-radius: 6px;
    font-size: 14px;
    line-height: 1.5;
}

.dev-note strong {
    color: #2c7da0;
    font-size: 15px;
}
/* Адаптивность */
@media (max-width: 1200px) {
    .blog-single-page {
        grid-template-columns: 250px 1fr;
    }
    
    .post-toc {
        display: none;
    }
}

@media (max-width: 768px) {
    .blog-single-page {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .blog-single-sidebar {
        position: static;
        max-height: none;
    }
    
    .blog-single-main {
        max-height: none;
    }
    
    .post-navigation {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}
</style>

<script>
// ========== ФУНКЦИОНАЛ ДЛЯ СТРАНИЦЫ БЛОГА ==========

document.addEventListener('DOMContentLoaded', function() {
    const postId = '<?= addslashes($postId) ?>';
    
    // Сохраняем, что мы сейчас читаем эту запись
    localStorage.setItem('currentlyReading', postId);
    
    // Кнопка возврата к списку
    const backButton = document.getElementById('back-to-list');
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            e.preventDefault();
            localStorage.setItem('lastViewedPost', postId);
            window.location.href = '/articles';
        });
    }
    
    // Кнопка возврата внизу
    const backBtnBottom = document.querySelector('.back-btn');
    if (backBtnBottom) {
        backBtnBottom.addEventListener('click', function(e) {
            e.preventDefault();
            localStorage.setItem('lastViewedPost', postId);
            window.location.href = '/articles';
        });
    }
    
    // Активная навигация в правой колонке
    const tocLinks = document.querySelectorAll('.toc-content a[href^="#"]');
    
    function updateActiveTocLink() {
        const scrollTop = document.querySelector('.blog-single-main').scrollTop;
        const viewportHeight = document.querySelector('.blog-single-main').clientHeight;
        const triggerPosition = viewportHeight * 0.3;
        
        let activeLink = null;
        
        tocLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href.startsWith('#')) {
                const id = href.substring(1);
                const element = document.getElementById(id);
                
                if (element) {
                    const rect = element.getBoundingClientRect();
                    const mainScrollTop = document.querySelector('.blog-single-main').scrollTop;
                    const relativeTop = rect.top + mainScrollTop;
                    
                    if (relativeTop <= scrollTop + triggerPosition) {
                        activeLink = link;
                    }
                }
            }
        });
        
        // Обновляем активную ссылку
        tocLinks.forEach(link => {
            if (link === activeLink) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }
    
    // Плавная прокрутка при клике на содержание
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('href').substring(1);
            const element = document.getElementById(id);
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Следим за прокруткой
    const mainContent = document.querySelector('.blog-single-main');
    if (mainContent) {
        mainContent.addEventListener('scroll', updateActiveTocLink);
        updateActiveTocLink(); // Инициализация
    }
    
    // Горячие клавиши
    document.addEventListener('keydown', function(e) {
        if (e.altKey) {
            <?php if ($prevEntry): ?>
            if (e.key === 'ArrowLeft') {
                window.location.href = '/articles/<?= addslashes($prevEntry['id']) ?>';
            }
            <?php endif; ?>
            
            <?php if ($nextEntry): ?>
            if (e.key === 'ArrowRight') {
                window.location.href = '/articles/<?= addslashes($nextEntry['id']) ?>';
            }
            <?php endif; ?>
            
            // B - возврат к списку
            if (e.key === 'b' || e.key === 'B' || e.key === 'и' || e.key === 'И') {
                localStorage.setItem('lastViewedPost', postId);
                window.location.href = '/articles';
            }
        }
    });
    
    // Обработка якоря из URL
    const hash = window.location.hash.substring(1);
    if (hash) {
        setTimeout(() => {
            const element = document.getElementById(hash);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }, 100);
    }
});
</script>