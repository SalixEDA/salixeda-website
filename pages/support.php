<?php
// pages/support.php

// Подключаем Parsedown для парсинга MD
require_once 'pages/Parsedown.php';

// Инициализация сессии
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Получаем или создаем ID пользователя
if (!isset($_SESSION['support_user_id'])) {
    // Генерируем уникальный ID
    $prefix = 'SUP';
    $random = strtoupper(bin2hex(random_bytes(4)));
    $timestamp = date('Ymd');
    $_SESSION['support_user_id'] = $prefix . $timestamp . $random;
}

$userId = $_SESSION['support_user_id'];
$dialogFile = __DIR__ . '/../dialogs/' . $userId . '.md';


// ============================================
// 1. ОБРАБОТКА НОВОГО СООБЩЕНИЯ
// ============================================

// ============================================
// 4. ОБРАБОТКА POST ЗАПРОСА (с защитой от дублирования)
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userMessage = trim($_POST['message']);
    $messageHash = md5($userMessage); // Хеш сообщения для сравнения

    // Проверяем не отправляли ли уже такое сообщение
    if( !empty($userMessage) && $_SESSION['last_message_hash'] !== $messageHash ) {

        // Сохраняем хеш последнего сообщения в cookie
        $_SESSION['last_message_hash'] = $messageHash;

        // Очищаем сообщение
        $cleanMessage = htmlspecialchars($userMessage, ENT_QUOTES, 'UTF-8');

        // Формируем вопрос
        $question = "\n\n## Вопрос от " . date('Y-m-d H:i:s') . "\n\n" . $cleanMessage . "\n---\n";

        // Сохраняем в файл
        if (file_exists($dialogFile)) {
            file_put_contents($dialogFile, $question, FILE_APPEND);
        } else {
            $header = "# Диалог поддержки\n\n**ID пользователя:** " . $userId . "\n**Создан:** " . date('Y-m-d H:i:s') . "\n\n---\n\n";
            file_put_contents($dialogFile, $header . $question);
        }

        // Добавляем автоматический ответ
        $autoResponse = "\n\n## Ответ от " . date('Y-m-d H:i:s') . "\n\n" . getAutoResponse($lang) . "\n---\n";
        file_put_contents($dialogFile, $autoResponse, FILE_APPEND);
    }
}



/*
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userMessage = trim($_POST['message']);

    if (!empty($userMessage)) {
        // Очищаем сообщение от вредоносного кода
        $cleanMessage = htmlspecialchars($userMessage, ENT_QUOTES, 'UTF-8');

        // Формируем вопрос
        $question = "\n\n## Вопрос от " . date('Y-m-d H:i:s') . "\n\n" . $cleanMessage . "\n";

        // Если файл существует, добавляем вопрос
        if (file_exists($dialogFile)) {
            file_put_contents($dialogFile, $question, FILE_APPEND);
        } else {
            // Создаем новый файл с заголовком
            $header = "# Диалог поддержки\n\n**ID пользователя:** " . $userId . "\n**Создан:** " . date('Y-m-d H:i:s') . "\n\n---\n\n";
            file_put_contents($dialogFile, $header . $question);
        }

        // Добавляем автоматический ответ
        $autoResponse = "\n\n## Ответ от " . date('Y-m-d H:i:s') . "\n\n" . getAutoResponse($lang) . "\n\n---\n\n";
        file_put_contents($dialogFile, $autoResponse, FILE_APPEND);

        // ВАЖНО: правильный редирект с полным URL
        $redirectUrl = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://')
                     . $_SERVER['HTTP_HOST']
                     . '/support';

        header('Location: ' . $redirectUrl);
        exit;
    }
} */

// ============================================
// 4. ЗАГРУЗКА И ПАРСИНГ ДИАЛОГА (НОВАЯ ВЕРСИЯ)
// ============================================
$dialogHtml = '';
if (file_exists($dialogFile)) {
    $mdContent = file_get_contents($dialogFile);

    // Разбиваем MD файл на блоки вопросов и ответов
    $blocks = parseDialogBlocks($mdContent);

    foreach ($blocks as $block) {
        if ($block['type'] === 'question') {
            $dialogHtml .= '
            <div class="chat-message user-message">
                <div class="message-header">
                    <span class="message-author">Вы</span>
                    <span class="message-time">' . $block['time'] . '</span>
                </div>
                <div class="message-bubble">
                    ' . $block['content'] . '
                </div>
            </div>';
        } elseif ($block['type'] === 'answer') {
            $dialogHtml .= '
            <div class="chat-message bot-message">
                <div class="message-header">
                    <span class="message-author">Поддержка</span>
                    <span class="message-time">' . $block['time'] . '</span>
                </div>
                <div class="message-bubble">
                    ' . $block['content'] . '
                </div>
            </div>';
        }
    }
} else {
    $dialogHtml = '<div class="welcome-message">' . getWelcomeMessage($lang, $userId) . '</div>';
}


// ============================================
// 3. ЗАГРУЗКА КОНТЕНТА СТРАНИЦЫ
// ============================================
$pageName = 'support';
$content = loadPageContent($pageName);
$mainContent = extractSection($content, 'MAIN');
$infoContent = extractSection($content, 'INFO');
?>

<div class="support-container">

    <!-- Заголовок с ID пользователя -->
    <div class="support-header">
        <h1><?= extractSection($content, 'PAGE_TITLE') ?></h1>
        <div class="user-id-info">
            <span class="user-id-label"><?= extractSection($content, 'USER_ID_LABEL') ?></span>
            <span class="user-id"><?= htmlspecialchars($userId) ?></span>
            <button class="copy-id-btn" onclick="copyUserId()" title="<?= extractSection($content, 'COPY_ID_TITLE') ?>">
                📋
            </button>
        </div>
        <p class="support-subtitle"><?= extractSection($content, 'PAGE_SUBTITLE') ?></p>
    </div>

    <!-- Информационный блок -->
    <?php if (!empty($infoContent)): ?>
    <div class="support-info">
        <?= $infoContent ?>
    </div>
    <?php endif; ?>

    <!-- ОКНО ДИАЛОГА -->
    <div class="dialog-window">
        <?= $dialogHtml ?>
    </div>

    <!-- ФОРМА ВВОДА -->
    <div class="chat-input">
        <form method="POST">
            <textarea
                name="message"
                placeholder="<?= extractSection($content, 'MESSAGE_PLACEHOLDER') ?>"
                rows="4"
                required
            ></textarea>
            <div class="input-controls">
                <button type="submit" class="send-btn">
                    <?= extractSection($content, 'BUTTON_SEND') ?>
                </button>
                <button type="button" class="refresh-btn" onclick="location.reload()">
                    <?= extractSection($content, 'BUTTON_REFRESH') ?>
                </button>
            </div>
        </form>
    </div>

</div>

<style>
/* СТИЛИ ДЛЯ ЧАТА */
.support-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 30px;
    box-sizing: border-box;
    background: #e8eaed;
    min-height: calc(100vh - 200px);
}

/* Заголовок */
.support-header {
    text-align: center;
    margin-bottom: 30px;
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.support-header h1 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 2em;
}

.user-id-info {
    background: #f8f9fa;
    padding: 12px 20px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin: 15px 0;
    border: 1px solid #e0e0e0;
}

.user-id-label {
    font-weight: bold;
    color: #7f8c8d;
}

.user-id {
    font-family: monospace;
    font-size: 16px;
    font-weight: bold;
    color: #3498db;
    letter-spacing: 1px;
}

.copy-id-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
    padding: 5px;
    border-radius: 5px;
    transition: all 0.2s;
}

.copy-id-btn:hover {
    background: #e0e0e0;
    transform: scale(1.1);
}

.support-subtitle {
    color: #7f8c8d;
    font-size: 0.95em;
    margin-top: 10px;
}

/* Информационный блок */
.support-info {
    background: #e8f4fc;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 25px;
    border-left: 4px solid #3498db;
    font-size: 14px;
    line-height: 1.6;
}

.support-info h2 {
    color: #2c3e50;
    font-size: 1.3em;
    margin-top: 0;
    margin-bottom: 15px;
}

.support-info h3 {
    color: #34495e;
    font-size: 1.1em;
    margin-top: 15px;
    margin-bottom: 10px;
}

.support-info ul, .support-info ol {
    margin-left: 20px;
    margin-bottom: 10px;
}

.support-info li {
    margin-bottom: 5px;
}

/* ========== ОКНО ЧАТА ========== */
.dialog-window {
    background: #e8eaed;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    min-height: 400px;
    max-height: 500px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Сообщения в чате */
.chat-message {
    display: flex;
    flex-direction: column;
    max-width: 80%;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Сообщения пользователя (справа) */
.user-message {
    align-self: flex-end;
}

.user-message .message-header {
    text-align: right;
}

.user-message .message-bubble {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    border-radius: 18px 18px 4px 18px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.user-message .message-bubble a {
    color: #fff;
    text-decoration: underline;
}

.user-message .message-bubble code {
    background: rgba(255,255,255,0.2);
    color: #fff;
}

.user-message .message-bubble pre {
    background: rgba(0,0,0,0.2);
}

/* Сообщения поддержки (слева) */
.bot-message {
    align-self: flex-start;
}

.bot-message .message-bubble {
    background: white;
    color: #333;
    border-radius: 18px 18px 18px 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    border: 1px solid #e0e0e0;
}

/* Заголовок сообщения */
.message-header {
    margin-bottom: 5px;
    padding: 0 5px;
}

.message-author {
    font-size: 12px;
    font-weight: bold;
    color: #5f6368;
}

.message-time {
    font-size: 10px;
    color: #9aa0a6;
    margin-left: 8px;
}

/* Контент сообщения */
.message-bubble {
    padding: 12px 16px;
    line-height: 1.5;
    font-size: 14px;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Стили для Markdown внутри сообщений */
.message-bubble p {
    margin: 0 0 10px 0;
}

.message-bubble p:last-child {
    margin-bottom: 0;
}

.message-bubble h1,
.message-bubble h2,
.message-bubble h3 {
    margin-top: 10px;
    margin-bottom: 8px;
}

.message-bubble h2 {
    font-size: 1.2em;
    border-left: 3px solid currentColor;
    padding-left: 10px;
}

.message-bubble h3 {
    font-size: 1.1em;
}

.message-bubble ul,
.message-bubble ol {
    margin: 5px 0 5px 20px;
    padding-left: 0;
}

.message-bubble li {
    margin-bottom: 5px;
}

.message-bubble code {
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
    padding: 2px 5px;
    border-radius: 4px;
}

.message-bubble pre {
    margin: 10px 0;
    padding: 10px;
    border-radius: 8px;
    overflow-x: auto;
}

.message-bubble pre code {
    padding: 0;
    background: none;
}

.message-bubble blockquote {
    margin: 10px 0;
    padding: 5px 15px;
    border-left: 3px solid currentColor;
    font-style: italic;
}

.message-bubble table {
    border-collapse: collapse;
    margin: 10px 0;
    width: 100%;
}

.message-bubble th,
.message-bubble td {
    border: 1px solid #ddd;
    padding: 6px 10px;
    text-align: left;
}

.message-bubble th {
    background: rgba(0,0,0,0.05);
    font-weight: bold;
}

.message-bubble hr {
    margin: 15px 0;
    border: none;
    border-top: 1px solid #e0e0e0;
}

.message-bubble a {
    text-decoration: none;
}

.message-bubble a:hover {
    text-decoration: underline;
}

.message-bubble img {
    max-width: 100%;
    border-radius: 8px;
}

.user-message .message-bubble pre {
    background: rgba(0,0,0,0.2);
}

.user-message .message-bubble code {
    background: rgba(255,255,255,0.2);
}

.user-message .message-bubble blockquote {
    border-left-color: rgba(255,255,255,0.5);
}

/* Приветственное сообщение */
.welcome-message {
    text-align: center;
    padding: 40px;
    background: white;
    border-radius: 15px;
    color: #5f6368;
    font-size: 1.1em;
}

.welcome-message h2 {
    color: #2c3e50;
    margin-bottom: 15px;
}

.welcome-message code {
    background: #f0f0f0;
    padding: 2px 6px;
    border-radius: 4px;
    font-family: monospace;
}

/* Форма ввода */
.chat-input {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.chat-input textarea {
    width: 100%;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 12px;
    font-size: 14px;
    resize: vertical;
    font-family: inherit;
    margin-bottom: 15px;
    box-sizing: border-box;
    transition: all 0.2s;
}

.chat-input textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.chat-input textarea:disabled {
    background: #f5f5f5;
    cursor: not-allowed;
}

.input-controls {
    display: flex;
    gap: 10px;
}

.send-btn, .refresh-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.send-btn {
    background: #3498db;
    color: white;
    flex: 1;
}

.send-btn:hover:not(:disabled) {
    background: #2980b9;
    transform: translateY(-1px);
}

.send-btn:disabled {
    background: #95a5a6;
    cursor: not-allowed;
}

.refresh-btn {
    background: #5f6368;
    color: white;
}

.refresh-btn:hover {
    background: #4a4e52;
}

/* Скроллбар */
.dialog-window::-webkit-scrollbar {
    width: 8px;
}

.dialog-window::-webkit-scrollbar-track {
    background: #dadce0;
    border-radius: 10px;
}

.dialog-window::-webkit-scrollbar-thumb {
    background: #9aa0a6;
    border-radius: 10px;
}

.dialog-window::-webkit-scrollbar-thumb:hover {
    background: #80868b;
}

/* Сообщения об успехе/ошибке */
.success-message {
    background: #d4edda;
    color: #155724;
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    border-left: 4px solid #28a745;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    border-left: 4px solid #dc3545;
}

.warning-message {
    background: #fff3cd;
    color: #856404;
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    border-left: 4px solid #ffc107;
}
</style>

<script>
// Копирование ID пользователя
function copyUserId() {
    const userId = document.querySelector('.user-id').textContent;
    navigator.clubWriteText(userId).then(() => {
        // Показываем уведомление
        const btn = document.querySelector('.copy-id-btn');
        const originalText = btn.textContent;
        btn.textContent = '✓';
        btn.style.color = '#27ae60';
        setTimeout(() => {
            btn.textContent = originalText;
            btn.style.color = '';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
        alert('<?= extractSection($content, 'COPY_ERROR') ?>');
    });
}

// Автопрокрутка вниз при загрузке
document.addEventListener('DOMContentLoaded', function() {
    const dialogWindow = document.querySelector('.dialog-window');
    if (dialogWindow) {
        dialogWindow.scrollTop = dialogWindow.scrollHeight;
    }

    // Фокус на поле ввода
    const textarea = document.querySelector('textarea[name="message"]');
    if (textarea) {
        textarea.focus();
    }

    // Автоматическая высота текстового поля
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 200) + 'px';
    });
});
</script>

<?php
// ============================================
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
// ============================================

function getAutoResponse($lang) {
    if ($lang === 'ru') {
        return "**Спасибо за ваш вопрос!**\n\n"
             . "Ваш вопрос будет передан разработчику. Он отвечает в течение 2-х дней. "
             . "Разработчик один, поэтому просьба отнестись с пониманием.\n\n"
             . "Ответ появится здесь же, в этом диалоге. Вы можете обновить страницу позже, "
             . "чтобы увидеть ответ.\n\n"
             . "**Важно:** Для быстрого решения проблемы, пожалуйста, указывайте:\n"
             . "- Версию продукта\n"
             . "- Операционную систему\n"
             . "- Шаги для воспроизведения проблемы (если есть)\n\n"
             . "Спасибо за терпение! 🙏";
    } else {
        return "**Thank you for your question!**\n\n"
             . "Your question will be forwarded to the developer. He responds within 2 days. "
             . "There's only one developer, so please be understanding.\n\n"
             . "The answer will appear here, in this dialog. You can refresh the page later "
             . "to see the response.\n\n"
             . "**Important:** For quick problem resolution, please include:\n"
             . "- Product version\n"
             . "- Operating system\n"
             . "- Steps to reproduce the issue (if any)\n\n"
             . "Thank you for your patience! 🙏";
    }
}

function getWelcomeMessage($lang) {
    if ($lang === 'ru') {
        return "# Добро пожаловать в систему поддержки!\n\n"
             . "Задайте ваш вопрос в форме ниже. Разработчик ответит вам в течение 2-х дней.\n\n"
             . "Ваш ID диалога: **" . $_SESSION['support_user_id'] . "**\n\n"
             . "Сохраните этот ID, он понадобится для отслеживания ответа.";
    } else {
        return "# Welcome to Support System!\n\n"
             . "Ask your question in the form below. The developer will respond within 2 days.\n\n"
             . "Your dialog ID: **" . $_SESSION['support_user_id'] . "**\n\n"
             . "Save this ID, it will be needed to track the response.";
    }
}



// ============================================
// ФУНКЦИЯ ПАРСИНГА ДИАЛОГА
// ============================================
function parseDialogBlocks($mdContent) {
    $blocks = [];

    // Разбиваем по маркерам вопросов и ответов
    $pattern = '/## (Вопрос|Ответ|Question|Answer) от (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\n\n(.*?)(?=\n## |$)/s';
    preg_match_all($pattern, $mdContent, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $type = (strpos($match[1], 'Вопрос') !== false || strpos($match[1], 'Question') !== false) ? 'question' : 'answer';
        $time = $match[2];
        $content = $match[3];

        // Парсим Markdown в HTML
        $parsedown = new Parsedown();
        $htmlContent = $parsedown->text(trim($content));

        $blocks[] = [
            'type' => $type,
            'time' => $time,
            'content' => $htmlContent
        ];
    }

    return $blocks;
}

?>

