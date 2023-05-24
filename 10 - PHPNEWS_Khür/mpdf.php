<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once 'Model/ArticleRepository.php';
require_once 'Model/AuthorRepository.php';
require_once 'Model/CategoryRepository.php';
require_once 'Model/Database.php';

    $db = new Database();
    $ar = new ArticleRepository($db);
    $authorRepository = new AuthorRepository($db);
    $cr = new CategoryRepository($db);
    $mpdf = new \Mpdf\Mpdf();

    $articleId = $_GET['id'];
    if (isset($articleId))
    {
        $article = $ar->getArticle($articleId);
        $formatDate = date("d.m.Y H:i", strtotime($article['created_at']));
        $pdf = "<h1 class='fw-semibold'>" . $article['title'] . "</h1>";
        $pdf .= "<div class='articles'>";
        $pdf .= "<h5>". "Autor: " . $article['authorName'] ." ". $article['authorSurname'] ."</h5>";
        if (isset($article['file_name']))
        {
            $pdf .= "<img style='height: 300px; width: 300px' src='" . $article["file_name"] . "'>";
        }
        $pdf .= "<p>" . $article['text'] . "</p>";
        $pdf .= "<h6>" . $formatDate . "</h6>";
        $pdf .= "</div>";
        $stylesheet = file_get_contents('style.css');
        $mpdf->WriteHTML($pdf);
        $mpdf->Output();
    }
    else
    {
        header('Location: index.php');
        die();
    }

    header('Location: articleDetail.php?id=' . $articleId);
    die();




