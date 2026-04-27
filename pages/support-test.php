<?php
// test_support.php - полная отладка

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Отладка поддержки</h1>\n";

// 1. Проверка директории
$dialogsDir = __DIR__ . '/../dialogs/';
echo "<h2>1. Проверка директории</h2>\n";
echo "Путь: " . $dialogsDir . "<br>\n";
echo "Существует: " . (file_exists($dialogsDir) ? 'Да' : 'Нет') . "<br>\n";

if (!file_exists($dialogsDir)) {
    echo "Пробуем создать...<br>\n";
    if (mkdir($dialogsDir, 0777, true)) {
        echo "✅ Директория создана<br>\n";
        chmod($dialogsDir, 0777);
    } else {
        echo "❌ Не удалось создать директорию<br>\n";
    }
}

echo "Права: " . substr(sprintf('%o', fileperms($dialogsDir)), -4) . "<br>\n";
echo "Доступна запись: " . (is_writable($dialogsDir) ? 'Да' : 'Нет') . "<br>\n";

// 2. Проверка сессии
echo "<h2>2. Проверка сессии</h2>\n";
session_start();
$userId = 'TEST_' . date('YmdHis');
$_SESSION['test_user_id'] = $userId;
echo "ID пользователя: " . $userId . "<br>\n";
echo "Сессия сохранена: " . session_id() . "<br>\n";

// 3. Создание тестового файла
echo "<h2>3. Создание тестового файла</h2>\n";
$testFile = $dialogsDir . $userId . '.md';
echo "Файл: " . $testFile . "<br>\n";

$testContent = "# Тестовый диалог\n\n";
$testContent .= "**ID:** " . $userId . "\n";
$testContent .= "**Время:** " . date('Y-m-d H:i:s') . "\n\n";
$testContent .= "## Вопрос\n\n";
$testContent .= "Это тестовое сообщение\n\n";

$result = file_put_contents($testFile, $testContent);
if ($result !== false) {
    echo "✅ Файл создан, записано " . $result . " байт<br>\n";

    // Проверяем чтение
    $readContent = file_get_contents($testFile);
    echo "✅ Файл читается, размер: " . strlen($readContent) . " байт<br>\n";

    // Показываем содержимое
    echo "<h3>Содержимое файла:</h3>\n";
    echo "<pre>" . htmlspecialchars($readContent) . "</pre>\n";
} else {
    echo "❌ Не удалось создать файл<br>\n";
    echo "Ошибка: " . error_get_last()['message'] . "<br>\n";
}

// 4. Проверка POST запроса
echo "<h2>4. Тест отправки сообщения</h2>\n";
?>
<form method="POST">
    <textarea name="message" rows="3" cols="50" placeholder="Введите тестовое сообщение"></textarea><br>
    <button type="submit" name="test_submit">Отправить тест</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_submit'])) {
    $message = trim($_POST['message'] ?? '');
    echo "<h3>Результат POST запроса:</h3>\n";
    echo "Сообщение: " . htmlspecialchars($message) . "<br>\n";

    if (!empty($message)) {
        $postFile = $dialogsDir . 'POST_' . date('YmdHis') . '.txt';
        $postContent = "Время: " . date('Y-m-d H:i:s') . "\n";
        $postContent .= "Сообщение: " . $message . "\n";
        $postContent .= "SESSION_ID: " . session_id() . "\n";

        if (file_put_contents($postFile, $postContent)) {
            echo "✅ POST запрос сохранен в файл: " . $postFile . "<br>\n";
        } else {
            echo "❌ Не удалось сохранить POST запрос<br>\n";
        }
    }
}

// 5. Информация о сервере
echo "<h2>5. Информация о сервере</h2>\n";
echo "PHP версия: " . phpversion() . "<br>\n";
echo "Владелец процесса: " . get_current_user() . "<br>\n";
echo "Пользователь PHP: " . exec('whoami') . "<br>\n";
echo "Документ root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>\n";

// 6. Список существующих файлов
echo "<h2>6. Существующие файлы в dialogs/</h2>\n";
if (is_dir($dialogsDir)) {
    $files = scandir($dialogsDir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $filePath = $dialogsDir . $file;
            echo "- " . $file . " (" . filesize($filePath) . " байт, права: " . substr(sprintf('%o', fileperms($filePath)), -4) . ")<br>\n";
        }
    }
} else {
    echo "Директория не существует<br>\n";
}
?>

