<?php
// pages/video.php
$pageName = 'video';

// Используем шаблон
include 'pages/shorts.php';

?>




<style>
/* Стили для страницы видео */
.video-page {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
}

/* Заголовок страницы */
.video-header {
    text-align: center;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 2px solid #3498db;
}

.video-header h1 {
    font-size: 2em;
    color: #2c3e50;
    margin-bottom: 10px;
}

.video-subtitle {
    font-size: 1em;
    color: #7f8c8d;
}

/* Сетка видео - 2 колонки */
.video-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-top: 20px;
}

/* Карточка видео */
.video-card {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

/* При фокусе на видео внутри карточки - расширяем карточку */
.video-card:focus-within {
    grid-column: span 2;
}

/* Описание сверху */
.video-description {
    padding: 15px 15px 0 15px;
    font-size: 0.9em;
    color: #555;
    line-height: 1.4;
}

.video-description p {
    margin: 0 0 5px 0;
}

/* Контейнер видео */
.video-container {
    background: #000;
    padding: 15px;
}

/* Видео плеер */
.video-player {
    width: 100%;
    height: auto;
    display: block;
    background: #000;
}

/* Индикатор фокуса для карточки (опционально) */
.video-card:focus-within {
    box-shadow: 0 5px 20px rgba(52, 152, 219, 0.3);
}

</style>

