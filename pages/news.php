<?php
// pages/news.php

global $routeParams, $lang;

$params = $routeParams ? explode('/', $routeParams) : [];

// Если параметров нет - показываем список записей
if (empty($params)) {
    include 'newsList.php';
} 
// Если один параметр - показываем конкретную запись
elseif (count($params) === 1) {
    $postId = $params[0];
    include 'newsSingle.php';
}
// Если больше параметров - 404
else {
    include 'pages/404.php';
    http_response_code(404);
}
?>