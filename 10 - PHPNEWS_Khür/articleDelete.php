<?php
require_once 'Model/ArticleRepository.php';
require_once 'Model/Database.php';
require_once 'login_check.php';

$db = new Database();
$articleRepository = new ArticleRepository($db);

$articleId = $_GET['id'];

if (!isset($articleId)) {
    header('Location: index.php');
    die();
}

$articleRepository->deleteAllCategoriesFromArticle($articleId);
$articleRepository->deleteArticle($articleId);
header('Location: administration.php');


