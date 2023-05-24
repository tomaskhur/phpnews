<?php

require_once 'Model/AuthorRepository.php';
require_once 'Model/Database.php';
session_start();

$db = new Database();
$authorRepository = new AuthorRepository($db);

if (!isset($_SESSION['user']))
{
    header('Location: index.php');
    die();
}


