<?php
    require_once 'Model/AuthorRepository.php';
    require_once 'Model/Database.php';
    require_once 'login_check.php';

    session_start();

    $db = new Database();
    $authorRepository = new AuthorRepository($db);

    $authorId = $_GET['id'];

    if (!isset($authorId)) {
        header('Location: index.php');
        die();
    }

    $count = $authorRepository->cannotDeleteAuthor($authorId);

    if ($count['authorCount'] > 1) {
        header('Location: authorAdd.php');
        die();
    }

    $authorRepository->deleteAuthor($authorId);
    header('Location: authorAdd.php');
    die();


