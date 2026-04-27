<?php
// pages/video.php
$pageName = 'video';

// Используем шаблон
include 'pages/shorts.php';

?>




<style>
/* Стили страницы видео */
.video-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 30px 20px;
}

/* Заголовок */
.video-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid #3498db;
}

.video-header h1 {
    font-size: 2.2em;
    color: #2c3e50;
    margin-bottom: 10px;
}

.video-subtitle {
    font-size: 1em;
    color: #7f8c8d;
}

/* Сетка видео */
.video-grid {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

/* Карточка видео */
.video-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Контейнер видео */
.video-container {
    background: #000;
}

.video-player {
    width: 100%;
    height: auto;
    display: block;
}

/* Информация о видео */
.video-info {
    padding: 15px 20px;
}

.video-title {
    font-size: 1.2em;
    color: #2c3e50;
    margin: 0 0 8px 0;
    font-weight: 600;
}

.video-description {
    font-size: 0.9em;
    color: #666;
    line-height: 1.4;
}

.video-description p {
    margin: 0 0 5px 0;
}

.video-description p:last-child {
    margin-bottom: 0;
}

/* Сообщение об отсутствии видео */
.no-videos {
    text-align: center;
    padding: 60px 20px;
    background: #f8f9fa;
    border-radius: 8px;
    color: #95a5a6;
    font-size: 1.1em;
}
</style>

