<?php
// config.php

//Разбираемся со статистикой
if( isset($_COOKIE['salixVisitorId']) ) {
  // Кука есть → считаем
  $visitorId = $_COOKIE['salixVisitorId'];
  $line = implode('|', [
        $visitorId,
        date('Y-m-d H:i:s'),
        $_SERVER['REQUEST_URI'],
        $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        $_SERVER['HTTP_REFERER'] ?? '',
        $_SERVER['REMOTE_ADDR'] ?? ''
    ]) . "\n";

  file_put_contents('/var/log/salixeda.org/visits.log', $line, FILE_APPEND | LOCK_EX);
} else {
  // Куки нет → генерируем, отправляем, но НЕ считаем
  $visitorId = bin2hex(random_bytes(16));
  setcookie('salixVisitorId', $visitorId, time() + 86400 * 365, '/', '', false, true);
  // ❌ Не передаем в статистику
}


// Запускаем сессию
session_start();

// Поддерживаемые языки
$supportedLanguages = ['ru', 'en'];
$defaultLanguage = 'ru';

// Определяем язык браузера
function getBrowserLanguage() {
    global $supportedLanguages, $defaultLanguage;
    
    $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en', 0, 2);
    return in_array($browserLang, $supportedLanguages) ? $browserLang : $defaultLanguage;
}


// ============================================
// 1. УСТАНАВЛИВАЕМ ЯЗЫК В СЕССИИ (если еще не установлен)
// ============================================
if (!isset($_SESSION['site_lang'])) {
    // Устанавливаем язык по умолчанию из браузера
    $_SESSION['site_lang'] = getBrowserLanguage();
}

// ============================================
// 2. ОБРАБАТЫВАЕМ СМЕНУ ЯЗЫКА (если пользователь нажал кнопку)
// ============================================
if (isset($_GET['lang']) && in_array($_GET['lang'], $supportedLanguages)) {
    // Меняем язык в сессии
    $_SESSION['site_lang'] = $_GET['lang'];
    
    // Удаляем параметр lang из URL, чтобы при обновлении не менялся обратно
    $currentUrl = str_replace(['?lang=' . $_GET['lang'], '&lang=' . $_GET['lang']], '', $_SERVER['REQUEST_URI']);
    
    // Перенаправляем на ту же страницу без параметра lang
    header('Location: ' . $currentUrl);
    exit;
}

// ============================================
// 3. ПОЛУЧАЕМ ТЕКУЩИЙ ЯЗЫК ИЗ СЕССИИ
// ============================================
$lang = $_SESSION['site_lang']; // Всегда есть после шага 1

// ============================================
// 4. ПРОСТЕЙШИЙ РОУТИНГ (как вы и хотели)
// ============================================
/*
// Ультра-минимальная версия
$url = trim($_GET['url'] ?? '', '/');



// Защита от path traversal (попыток выйти за пределы pages/)
if( preg_match('/\.\.|\\\\|\/\/|^\//', $url) ) {
  $pageFile = 'pages/404.php';
  http_response_code(404);
  }
else {
  $urlParts = explode('#', $url);
  $url = $urlParts[0]; // download
  $pageAnchor = $urlParts[1] ?? null; // linux или null
  
  // Главная или любая другая страница
  $pageFile = ($url === '') ? 'pages/home.php' : 'pages/' . $url . '.php';

  // Проверка существования файла
  if(!file_exists($pageFile) ) {
    $pageFile = 'pages/404.php';
    http_response_code(404);
    }
  }

// Определяем текущую страницу для выделения активного пункта меню
$currentPage = $url === '' ? 'home' : $url;
*/

$url = trim($_GET['url'] ?? '', '/');

// Защита от path traversal
if( preg_match('/\.\.|\\\\|\/\/|^\//', $url) ) {
  $pageFile = 'pages/404.php';
  $pageAnchor = null;
  $routeParams = '';
  http_response_code(404);
  } 
else {
  // Разделяем URL и якорь
  $urlParts = explode('#', $url);
  $urlPath = $urlParts[0]; // doc/userGuide/section5
  $pageAnchor = $urlParts[1] ?? null; // linux или null
    
  // Разбиваем путь на части
  $pathParts = $urlPath === '' ? [] : explode('/', $urlPath);
    
  // Ищем подходящий файл по иерархии
  $pageFile = null;
  $routeParams = '';
    
  // Пробуем найти файл по полному пути
  $fullPath = 'pages/' . $urlPath . '.php';
  if( $urlPath !== '' && file_exists($fullPath) ) {
    // Нашли точное совпадение (pages/doc/userGuide/section5.php)
    $pageFile = $fullPath;
    $routeParams = '';
    } 
  else {
    // Ищем по иерархии сверху вниз
    $searchPath = 'pages/';
        
    for ($i = 0; $i < count($pathParts); $i++) {
      $currentPart = $pathParts[$i];
      $testFile = $searchPath . $currentPart . '.php';
      $testFolder = $searchPath . $currentPart . '/';
            
      // 1. Проверяем файл (pages/doc.php)
      if( file_exists($testFile) ) {
        // Нашли файл-обработчик
        $pageFile = $testFile;
        // Остаток пути передаем как параметры
        $routeParams = implode('/', array_slice($pathParts, $i + 1));
        break;
        }
      // 2. Проверяем папку (pages/doc/)
      if (is_dir($testFolder)) {
        $searchPath = $testFolder;
        continue;
        }
      // 3. Если не нашли ни файла, ни папки - 404
      break;
      }
        
    // Если дошли до конца и ничего не нашли
    if (!$pageFile && $urlPath !== '') {
      // Главная страница
      if ($urlPath === '') {
        $pageFile = 'pages/home.php';
        } 
      else {
        $pageFile = 'pages/404.php';
        http_response_code(404);
        }
      } 
    elseif (!$pageFile) {
      $pageFile = 'pages/home.php';
      }
    }
    
  // Если все еще не определили файл (например, пустой URL)
  if (!$pageFile) {
    $pageFile = 'pages/home.php';
    }
  }

// Теперь у нас есть:
// - $pageFile - файл для подключения (pages/doc.php)
// - $routeParams - оставшаяся часть пути (userGuide/section5)
// - $pageAnchor - якорь (#linux)

// Определяем текущую страницу для меню (первая часть пути или 'home')
//$currentPage = !empty($pathParts) ? $pathParts[0] : 'home';
$currentPage = 'home';


// ============================================
// ОБЩИЕ ФУНКЦИИ ДЛЯ ВСЕХ СТРАНИЦ
// ============================================

// index.php - ПРОСТЫЕ ФУНКЦИИ С МАРКЕРАМИ

/**
 * Извлекает секцию между маркерами <!-- SECTION_START --> и <!-- SECTION_END -->
 */
function extractSection($html, $sectionName) {
    $startMarker = "<!-- {$sectionName}_START -->";
    $endMarker = "<!-- {$sectionName}_END -->";
    
    $startPos = strpos($html, $startMarker);
    if ($startPos === false) {
        return '';
    }
    
    $startPos += strlen($startMarker);
    $endPos = strpos($html, $endMarker, $startPos);
    
    if ($endPos === false) {
        return '';
    }
    
    return trim(substr($html, $startPos, $endPos - $startPos));
}

/**
 * Извлекает несколько секций за один раз
 */
function extractSections($html, $sectionNames) {
    $result = [];
    foreach ($sectionNames as $name) {
        $result[$name] = extractSection($html, $name);
    }
    return $result;
}


function extractIdent($content, $type) {
  // Экранируем спецсимволы в типе для безопасного использования в регулярном выражении
  $escapedType = preg_quote($type, '/');

  // Ищем комментарий вида <!--{type} любой текст до -->
  // .*? - любой текст (включая пробелы) в нежадном режиме
  $pattern = '/<!--' . $escapedType . '\s+(.*?)\s*-->/';

  if (preg_match($pattern, $content, $matches)) {
    // Обрезаем пробелы по краям
    return trim($matches[1]);
    }

  return '';
  }


/**
 * Загружает контент страницы из файла
 * @param string $pageName Имя страницы (без пути и расширения)
 * @param string $lang Язык
 * @param string $contentDir Директория с контентом
 * @return string HTML контент или пустая строка
 */
function loadPageContent($pageName) {
  global $lang;
  $file = "content/$pageName-$lang.html";
    
  // Если нет файла для текущего языка, пробуем русский
  if (!file_exists($file) && $lang != 'ru') {
    $file = "content/$pageName-ru.html";
    }
    
  return file_exists($file) ? file_get_contents($file) : '';
  }

?>
  
<!DOCTYPE html>
<html lang="<?= $lang ?>" data-page-anchor="<?= htmlspecialchars($pageAnchor ?? '') ?>">
<?php
echo <<<HTML
<!--
    Page file: {$pageFile}
    Route params: {$routeParams}
    Page anchor: {$pageAnchor}
-->
HTML;
?>
<body>
  <?php include 'common/header.php'; ?>
  
  <main>
       <?php 
        // Подключаем файл страницы
        // Внутри страницы будут доступны:
        // - $pageContent (весь HTML контент)
        // - $lang (текущий язык)
        // - Функции extractSection(), extractSections()
        // - Для главной: $galleryContent, $featuresContent, $sidebarContent
        include $pageFile; 
        ?>
  </main>
  
  <footer>
  </footer>
</body>
</html>