<?php
// pages/help.php
$pageName = 'help';

// Используем шаблон
include 'pages/shorts.php';

?>

<style>
/* ========== СТРАНИЦА RESOURCES (ДЕСКТОП) ========== */

.resources-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Сетка: 4 строго фиксированные колонки */
.resources-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin: 32px 0;
}

/* Карточка */
.resource-card {
    background: #ffffff;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    transition: all 0.2s ease;
}

.resource-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    border-color: #2c7da0;
}

/* Ссылка занимает всю карточку */
.resource-card a {
    display: block;
    text-decoration: none;
    color: inherit;
    padding: 24px 20px;
    text-align: center;
}

/* Иконка */
.resource-icon {
    height: 160px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.resource-icon img {
    max-width: 160px;
    max-height: 160px;
}

/* Заголовок */
.resource-card h2 {
    font-size: 18px;
    font-weight: 600;
    margin: 12px 0 8px 0;
    color: #2c3e50;
}

/* Текст */
.resource-card p {
    font-size: 14px;
    line-height: 1.5;
    color: #555;
    margin: 0 0 12px 0;
}

/* Тег */
.resource-tag {
    display: inline-block;
    background: #e8ecf0;
    color: #555;
    font-size: 11px;
    font-weight: 500;
    padding: 4px 12px;
    border-radius: 20px;
    letter-spacing: 0.3px;
    transition: all 0.2s;
}

.resource-card:hover .resource-tag {
    background: #2c7da0;
    color: white;
}

/* Блок подсказки */
.suggestion-box {
    background: #f0f6fa;
    border-left: 4px solid #2c7da0;
    padding: 16px 24px;
    border-radius: 6px;
    margin-top: 32px;
}

.suggestion-box a {
    color: #1e5a6e;
    text-decoration: underline;
}

.suggestion-box a:hover {
    color: #0d3b48;
}

</style>
