<?php
// pages/articles.php

global $routeParams, $lang;

echo "<!-- Enter articles.php -->\n";

if( empty($routeParams) ) {
  echo "<!-- empty routeParams -->\n";
  include 'articleList.php';
} else {
  echo "<!-- Record found: {$routeParams} -->\n";
  $postId = $routeParams;
  include 'articleSingle.php';
}
?>