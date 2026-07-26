<?php
// pages/news.php

global $routeParams, $lang;

if( empty($routeParams) ) {
  include 'newsList.php';
} else {
  $postId = $routeParams;
  include 'newsSingle.php';
}
?>