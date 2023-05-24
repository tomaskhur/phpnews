<?php
require_once 'Model/CategoryRepository.php';
require_once 'Model/Database.php';
require_once 'login_check.php';

$db = new Database();
$categoryRepository = new CategoryRepository($db);

$categoryId = $_GET['id'];

$count = $categoryRepository->cannotDeleteCategory($categoryId);

if (!isset($categoryId)) {
    header('Location: index.php');
    die();
}

if ($count['categoryCount'] > 0) {
    header('Location: categoryAdd.php');
    die();
}

    $categoryRepository->deleteCategory($categoryId);
    header('Location: categoryAdd.php');
    die();

