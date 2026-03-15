<?php
// pages/articleList.php

// Загружаем список записей
$entriesFile = 'content/news/entries.json';
$entries = file_exists($entriesFile) ? json_decode(file_get_contents($entriesFile), true) : [];
$entries = $entries['entries'] ?? [];

// Сортируем по дате (новые сначала)
usort($entries, function($a, $b) {
    return strcmp($b['date'], $a['date']);
});

// Получаем 5 последних записей для правой колонки
$latestEntries = array_slice($entries, 0, 5);
?>

<div class="blog-page">
    <!-- Основной контент -->
    <main class="blog-main" id="blog-main">
        <h1><?= $lang == 'ru' ? 'Блог разработки' : 'Development Blog' ?></h1>
        
        <div class="blog-list" id="blog-list">
            <?php if (empty($entries)): ?>
                <p class="no-posts"><?= $lang == 'ru' ? 'Пока нет записей в блоге.' : 'No blog posts yet.' ?></p>
            <?php else: ?>
                <?php foreach ($entries as $entry): ?>
                <article class="blog-entry" id="post-<?= htmlspecialchars($entry['id']) ?>">
                    <h2>
                        <a href="/blog/<?= htmlspecialchars($entry['id']) ?>" 
                           class="post-link"
                           data-post-id="<?= htmlspecialchars($entry['id']) ?>">
                            <?= htmlspecialchars($entry['title'][$lang] ?? $entry['title']['ru'] ?? '') ?>
                        </a>
                    </h2>
                    
                    <div class="entry-meta">
                        <time datetime="<?= htmlspecialchars($entry['date']) ?>">
                            <?= date('d.m.Y', strtotime($entry['date'])) ?>
                        </time>
                        <?php if (!empty($entry['read_time'])): ?>
                            <span class="read-time">• <?= htmlspecialchars($entry['read_time']) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($entry['author'])): ?>
                            <span class="author">• <?= htmlspecialchars($entry['author']) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <p class="entry-excerpt">
                        <?= htmlspecialchars($entry['excerpt'][$lang] ?? $entry['excerpt']['ru'] ?? '') ?>
                    </p>
                    
                    <?php if (!empty($entry['tags'])): ?>
                    <div class="entry-tags">
                        <?php foreach ($entry['tags'] as $tag): ?>
                            <span class="tag"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <a href="/blog/<?= htmlspecialchars($entry['id']) ?>" class="read-more">
                        <?= $lang == 'ru' ? 'Читать дальше →' : 'Read more →' ?>
                    </a>
                </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
    
    <!-- Правая колонка -->
    <aside class="blog-sidebar">
        <div class="sidebar-section">
            <h3><?= $lang == 'ru' ? 'Последние записи' : 'Latest Posts' ?></h3>
            <ul class="latest-posts">
                <?php foreach ($latestEntries as $entry): ?>
                <li>
                    <a href="/blog/<?= htmlspecialchars($entry['id']) ?>">
                        <?= htmlspecialchars($entry['title'][$lang] ?? $entry['title']['ru'] ?? '') ?>
                    </a>
                    <small><?= date('d.m.Y', strtotime($entry['date'])) ?></small>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="sidebar-section">
            <h3><?= $lang == 'ru' ? 'Категории' : 'Categories' ?></h3>
            <ul class="categories">
                <li><a href="/blog?tag=release"><?= $lang == 'ru' ? 'Релизы' : 'Releases' ?></a></li>
                <li><a href="/blog?tag=tutorial"><?= $lang == 'ru' ? 'Уроки' : 'Tutorials' ?></a></li>
                <li><a href="/blog?tag=news"><?= $lang == 'ru' ? 'Новости' : 'News' ?></a></li>
                <li><a href="/blog?tag=development"><?= $lang == 'ru' ? 'Разработка' : 'Development' ?></a></li>
            </ul>
        </div>
        
        <div class="sidebar-section">
            <h3><?= $lang == 'ru' ? 'Подписаться' : 'Subscribe' ?></h3>
            <p><?= $lang == 'ru' ? 'Получайте уведомления о новых записях:' : 'Get notified about new posts:' ?></p>
            <form class="subscribe-form">
                <input type="email" placeholder="<?= $lang == 'ru' ? 'Ваш email' : 'Your email' ?>" required>
                <button type="submit"><?= $lang == 'ru' ? 'Подписаться' : 'Subscribe' ?></button>
            </form>
        </div>
    </aside>
</div>

<style>
/* ========== СТИЛИ БЛОГА ========== */

.blog-page {
    display: flex;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
    gap: 40px;
}

.blog-main {
    flex: 1;
    min-width: 0;
}

.blog-sidebar {
    width: 300px;
    flex-shrink: 0;
}

/* Список записей */
.blog-list {
    margin-top: 30px;
}

.blog-entry {
    background: white;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-left: 4px solid #3498db;
    transition: transform 0.3s, box-shadow 0.3s;
}

.blog-entry:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.blog-entry h2 {
    margin-top: 0;
    margin-bottom: 10px;
}

.blog-entry h2 a {
    color: #2c3e50;
    text-decoration: none;
}

.blog-entry h2 a:hover {
    color: #3498db;
}

.entry-meta {
    color: #7f8c8d;
    font-size: 14px;
    margin-bottom: 15px;
}

.entry-meta time {
    font-weight: bold;
}

.entry-excerpt {
    color: #34495e;
    line-height: 1.6;
    margin-bottom: 15px;
}

.entry-tags {
    margin-bottom: 15px;
}

.tag {
    display: inline-block;
    background: #ecf0f1;
    color: #7f8c8d;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    margin-right: 8px;
    margin-bottom: 5px;
}

.read-more {
    display: inline-block;
    color: #3498db;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.2s;
}

.read-more:hover {
    color: #2980b9;
    text-decoration: underline;
}

/* Правая колонка */
.sidebar-section {
    background: white;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.sidebar-section h3 {
    color: #2c3e50;
    margin-top: 0;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #3498db;
}

.latest-posts {
    list-style: none;
    padding: 0;
    margin: 0;
}

.latest-posts li {
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.latest-posts li:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.latest-posts a {
    display: block;
    color: #34495e;
    text-decoration: none;
    margin-bottom: 4px;
    font-weight: 500;
}

.latest-posts a:hover {
    color: #3498db;
}

.latest-posts small {
    color: #7f8c8d;
    font-size: 12px;
}

.categories {
    list-style: none;
    padding: 0;
    margin: 0;
}

.categories li {
    margin-bottom: 8px;
}

.categories a {
    display: block;
    padding: 8px 12px;
    color: #34495e;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.2s;
}

.categories a:hover {
    background: #f8f9fa;
    color: #3498db;
}

.subscribe-form input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 10px;
    box-sizing: border-box;
}

.subscribe-form button {
    width: 100%;
    padding: 10px;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

.subscribe-form button:hover {
    background: #2980b9;
}
</style>

<script>
// ========== СОХРАНЕНИЕ ПОЗИЦИИ ПРИ ВОЗВРАТЕ ==========

document.addEventListener('DOMContentLoaded', function() {
    const blogList = document.getElementById('blog-list');
    if (!blogList) return;
    
    // 1. СОХРАНЯЕМ ПОЗИЦИЮ ПРИ КЛИКЕ НА ЗАПИСЬ
    const postLinks = document.querySelectorAll('.post-link');
    postLinks.forEach(link => {
        link.addEventListener('click', function() {
            const postId = this.dataset.postId;
            
            // Сохраняем позицию прокрутки списка
            localStorage.setItem('blogScrollPosition', blogList.scrollTop);
            
            // Сохраняем ID текущей записи
            localStorage.setItem('lastViewedPost', postId);
            
            // Добавляем задержку перед переходом для сохранения
            setTimeout(() => {
                // Переход произойдет по ссылке
            }, 50);
        });
    });
    
    // 2. ВОССТАНАВЛИВАЕМ ПОЗИЦИЮ ПРИ ВОЗВРАТЕ
    const lastViewedPost = localStorage.getItem('lastViewedPost');
    const savedScrollPosition = localStorage.getItem('blogScrollPosition');
    
    if (lastViewedPost && savedScrollPosition !== null) {
        // Ищем запись в списке
        const targetPost = document.getElementById('post-' + lastViewedPost);
        
        if (targetPost) {
            // Плавно скроллим к записи
            setTimeout(() => {
                targetPost.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                
                // Восстанавливаем точную позицию прокрутки
                blogList.scrollTop = parseInt(savedScrollPosition);
            }, 100);
        }
        
        // Очищаем сохраненные данные
        localStorage.removeItem('blogScrollPosition');
        localStorage.removeItem('lastViewedPost');
    }
    
    // 3. СОХРАНЕНИЕ ПОЗИЦИИ ПРИ ПРОКРУТКЕ (на будущее)
    let scrollSaveTimer;
    blogList.addEventListener('scroll', function() {
        clearTimeout(scrollSaveTimer);
        scrollSaveTimer = setTimeout(() => {
            localStorage.setItem('blogListScroll', blogList.scrollTop);
        }, 200);
    });
    
    // 4. ВОССТАНОВЛЕНИЕ ПОЗИЦИИ ПРОКРУТКИ ПРИ ПЕРЕЗАГРУЗКЕ
    const savedListScroll = localStorage.getItem('blogListScroll');
    if (savedListScroll !== null && !lastViewedPost) {
        setTimeout(() => {
            blogList.scrollTop = parseInt(savedListScroll);
        }, 50);
    }
    
    // 5. ОЧИСТКА ПРИ ПЕРЕХОДЕ НА ДРУГУЮ СТРАНИЦУ
    window.addEventListener('beforeunload', function() {
        if (!document.querySelector('.post-link:hover')) {
            localStorage.removeItem('lastViewedPost');
        }
    });
});
</script>