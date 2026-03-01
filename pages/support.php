<?php
// pages/support.php

// Обработка кнопки очистки
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_chat'])  ) {
  $_SESSION['support_chat']['messages'] = [];
  }


// Инициализация истории диалога
if (!isset($_SESSION['support_chat'])) {
    $_SESSION['support_chat'] = [
        'messages' => []
    ];
}

// ============================================
// 1. ОБРАБОТКА ОТПРАВКИ СООБЩЕНИЯ (POST)
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message'])) {
        // Добавляем сообщение пользователя
        $userMessage = trim($_POST['message']);
        if (!empty($userMessage)) {
            $_SESSION['support_chat']['messages'][] = [
                'role' => 'user',
                'content' => $userMessage,
                'timestamp' => time(),
                'count' => 0
            ];
        }
    }
}

// ============================================
// 2. ПРОВЕРКА И ДОБАВЛЕНИЕ ОТВЕТА AI
// ============================================
$messages = $_SESSION['support_chat']['messages'];
$lastMessage = end($messages);

// Если последнее сообщение от пользователя и еще нет ответа AI
if( $lastMessage && $lastMessage['role'] === 'user' ) {
  // Получаем ключ последнего элемента
  $lastKey = array_key_last($_SESSION['support_chat']['messages']);

  // Увеличиваем счетчик на 1
  $_SESSION['support_chat']['messages'][$lastKey]['count']++;
    
  if( $_SESSION['support_chat']['messages'][$lastKey]['count'] > 3 ) {
        $_SESSION['support_chat']['messages'][] = [
            'role' => 'assistant',
            'content' => $lang == 'ru' ? 
                "Тестовый ответ AI на запрос: \"" . $lastMessage['content'] . "\"" :
                "Test AI response to query: \"" . $lastMessage['content'] . "\"",
            'timestamp' => time()
        ];
  }
}

// ============================================
// 3. ОПРЕДЕЛЕНИЕ РЕЖИМА (ФОРМА ИЛИ ОЖИДАНИЕ)
// ============================================
$messages = $_SESSION['support_chat']['messages'];
$lastMessage = end($messages);

// Режим ожидания, если последнее сообщение от пользователя
$isWaitingMode = ($lastMessage && $lastMessage['role'] === 'user');

// ============================================
// 4. ЗАГРУЗКА КОНТЕНТА
// ============================================
$pageName = 'support';
$content = loadPageContent($pageName);
$mainContent = extractSection($content, 'MAIN');
$supportHeader = extractSection($content, 'SUPPORT_HEADER');
$messageContent = extractSection($content, 'MESSAGE_CONTENT' );
$typingText = extractSection($content, 'TYPING_TEXT' );
$enterQuestion = extractSection($content, 'ENTER_QUESTION' );
$sendButton = extractSection($content, 'SEND_BUTTON' );
$clearButton = extractSection($content, 'CLEAR_BUTTON' );
$waitAnswer = extractSection($content, 'WAIT_ANSWER' );
$waitButton = extractSection($content, 'WAIT_BUTTON' );
?>

<!-- КОНТЕЙНЕР ПОДДЕРЖКИ -->
<div class="support-container">
    
    <!-- Заголовок -->
    <div class="support-header">
      <?= $supportHeader ?>
    </div>
    
    <!-- Информационный блок -->
    <?php if (!empty($mainContent)): ?>
    <div class="support-info">
        <?= $mainContent ?>
    </div>
    <?php endif; ?>
    
    <!-- ОКНО ЧАТА -->
    <div class="chat-window">
        <!-- История сообщений -->
        <?php if (empty($messages)): ?>
            <div class="message bot">
                <div class="message-content">
                   <?= $messageContent ?>
                </div>
                <div class="message-time"><?= date('H:i') ?></div>
            </div>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="message <?= $msg['role'] === 'user' ? 'user' : 'bot' ?>">
                    <div class="message-content"><?= htmlspecialchars($msg['content']) ?></div>
                    <div class="message-time"><?= date('H:i', $msg['timestamp']) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <!-- Индикатор "печатает" в режиме ожидания -->
        <?php if ($isWaitingMode): ?>
            <div class="message bot typing">
                <div class="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="typing-text">
                  <?= $typingText ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- ФОРМА ВВОДА -->
    <div class="chat-input">
        <?php if (!$isWaitingMode): ?>
            <!-- Режим ввода -->
            <form method="POST">
                <textarea name="message" 
                          placeholder="<? $enterQuestion ?>"
                          rows="2"
                          required></textarea>
                <div class="input-controls">
                    <button type="submit" class="send-btn">
                        <?= $sendButton ?>
                    </button>
                    <?php if (!empty($messages)): ?>
                    <button type="button" class="clear-btn" onclick="clearChat()">
                        <?= $clearButton ?>
                    </button>
                    <?php endif; ?>
                </div>
            </form>
        <?php else: ?>
            <!-- Режим ожидания -->
            <div class="chat-input waiting">
                <textarea disabled 
                          placeholder="<?= $waitAnswer ?>"
                          rows="2"></textarea>
                <div class="input-controls">
                    <button type="button" class="send-btn" disabled>
                        <?= $waitButton ?>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
</div>

<style>
/* СТИЛИ ПОДДЕРЖКИ (ТОЛЬКО ДЛЯ ДЕСКТОП) */
.support-container {
    width: 800px;
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
}

.support-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e0e0e0;
}

.support-header h1 {
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 2em;
}

.support-subtitle {
    color: #7f8c8d;
    font-size: 1em;
}

.support-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
    border-left: 4px solid #3498db;
    box-sizing: border-box;
}

/* Окно чата */
.chat-window {
    background: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    height: 400px;
    overflow-y: auto;
    box-sizing: border-box;
}

/* Сообщения */
.message {
    margin-bottom: 15px;
    max-width: 80%;
    animation: fadeIn 0.3s ease;
    box-sizing: border-box;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.message.bot {
    margin-right: auto;
}

.message.user {
    margin-left: auto;
}

.message-content {
    padding: 10px 15px;
    border-radius: 18px;
    line-height: 1.4;
    font-size: 14px;
    box-sizing: border-box;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

.message.bot .message-content {
    background: #f0f0f0;
    color: #333;
    border: 1px solid #ddd;
}

.message.user .message-content {
    background: #3498db;
    color: white;
    border: 1px solid #2980b9;
}

.message-time {
    font-size: 11px;
    color: #95a5a6;
    margin-top: 5px;
    text-align: right;
}

/* Индикатор печатания */
.message.typing {
    opacity: 0.8;
}

.typing-indicator {
    display: flex;
    gap: 5px;
    padding: 10px 15px;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #3498db;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.6; }
    30% { transform: translateY(-5px); opacity: 1; }
}

.typing-text {
    color: #7f8c8d;
    font-size: 12px;
    margin-top: 5px;
    text-align: center;
}

/* ФОРМА ВВОДА */
.chat-input {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    box-sizing: border-box;
    width: 100%;
}

.chat-input form {
    width: 100%;
    box-sizing: border-box;
}

/* Текстовое поле */
.chat-input textarea {
    width: 100%;
    box-sizing: border-box;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    padding: 12px;
    font-size: 14px;
    resize: none;
    font-family: inherit;
    margin-bottom: 10px;
    display: block;
}

.chat-input textarea:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.chat-input textarea:disabled {
    background: #f5f5f5;
    color: #95a5a6;
    cursor: not-allowed;
    border-color: #ddd;
}

/* Контейнер для кнопок */
.input-controls {
    display: flex;
    gap: 10px;
    width: 100%;
    box-sizing: border-box;
}

/* Кнопки */
.send-btn {
    background: #3498db;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    cursor: pointer;
    flex: 1;
    box-sizing: border-box;
    font-weight: bold;
    transition: background 0.2s;
}

.send-btn:hover:not(:disabled) {
    background: #2980b9;
}

.send-btn:disabled {
    background: #95a5a6;
    cursor: not-allowed;
}

.clear-btn {
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-size: 14px;
    cursor: pointer;
    box-sizing: border-box;
    font-weight: bold;
    transition: background 0.2s;
}

.clear-btn:hover {
    background: #c0392b;
}

/* Для режима ожидания */
.chat-input.waiting {
    opacity: 0.7;
}

/* Фиксированные размеры для десктопа */
.support-container {
    min-width: 800px;
    max-width: 800px;
}

.chat-window {
    min-height: 400px;
    max-height: 400px;
}
</style>
  
<script>
// Функция очистки чата
function clearChat() {
    if (confirm('<?= $lang == "ru" ? "Очистить историю диалога?" : "Clear conversation history?" ?>')) {
        // Создаем скрытую форму для отправки запроса
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'clear_chat';
        input.value = '1';
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}


// Авто-высота текстового поля
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.querySelector('textarea[name="message"]');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
        textarea.focus();
    }
    
    // Автопрокрутка вниз
    const chatWindow = document.querySelector('.chat-window');
    if (chatWindow) {
        chatWindow.scrollTop = chatWindow.scrollHeight;
    }
});

<?php if ($isWaitingMode): ?>
// Автообновление страницы каждые 5 секунд в режиме ожидания
setTimeout(function() {
    // Используем window.location.href вместо location.reload()
    // Это гарантирует GET-запрос
    window.location.href = '/support';
}, 5000);
<?php endif; ?>
</script>