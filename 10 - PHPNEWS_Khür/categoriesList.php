<?php
    require_once 'Model/Database.php';
    require_once 'Model/CategoryRepository.php';
    require_once 'Model/AuthorRepository.php';

    session_start();

    $db = new Database();
    $cr = new CategoryRepository($db);
    $authorRepository = new AuthorRepository($db);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <title>PHP NEWS - Category</title>
</head>
<body>
<?php
    $categoryId = $_GET['id'];
    $categoryArticles = $cr->getCategoriesArticles($categoryId);
    $categoryName = $cr->getCategory($categoryId);
?>

<nav class="my-navbar navbar p-3 navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand fs-4 fw-bold navbar-logo" href="index.php"><img src="images/php_news.png"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link my-fontsize fw-semibold active" aria-current="page" href="allArticles.php">Zprávy</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link active my-fontsize fw-semibold dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Kategorie
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        $categories = $cr->getCategories();
                        foreach ($categories as $category): ?>
                            <li><a class="dropdown-item" href="categoriesList.php?id= <?= $category['id'] ?>"><?= $category['name'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link active my-fontsize fw-semibold dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Autoři
                    </a>
                    <ul class="dropdown-menu">
                        <?php
                        $authors = $authorRepository->getAuthors();
                        foreach ($authors as $author): ?>
                            <li><a class="dropdown-item" href="authorDetail.php?id=<?= $author['id'] ?>"><?= $author['name'] . ' ' . $author['surname'] ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <?php if (isset($_SESSION['user'])) : ?>
                    <li class="nav-item my-fontsize fw-semibold">
                        <a class="nav-link active" href="administration.php">Administrace</a>
                    </li>
                    <li class="nav-item my-fontsize fw-semibold">
                        <a class="nav-link active" href="articleAdd.php">Přidat článek</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link active my-fontsize fw-semibold dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?= $_SESSION['user']['name']. " " . $_SESSION['user']['surname'] ?> &#128075;
                        </a>
                        <ul class="dropdown-menu">
                            <a class="dropdown-item" href="changePass.php?id=<?= $_SESSION['user']['id'] ?>">Změnit heslo</a>
                        </ul>
                    </li>
                    <li class="nav-item my-fontsize fw-semibold">
                        <a class="nav-link active" href="logout.php"> Odhlásit se <i class="fa-solid fa-right-from-bracket"></i></a>
                    </li>
                <?php endif; ?>

                <?php if (!isset($_SESSION['user'])) : ?>
                    <li class="nav-item my-fontsize fw-semibold">
                        <a class="nav-link active" href="login.php">Přihlášení <i class="fa-solid fa-user"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<header>
    <div class="header-container">
        <h1 class="fw-semibold">Články z kategorie</h1>
        <h5 class="fw-semibold"><?= $categoryName['name'] ?></h5>
    </div>
</header>

<?php if (empty($categoryArticles)): ?>
    <div class="container">
        <div class="row">
                <h3 class="fw-semibold mt-5 d-flex justify-content-center">V této kategorii nejsou žádné články</h3>
        </div>
    </div>
<?php endif; ?>

<main>
    <?php foreach ($categoryArticles as $categoryArticle): ?>
        <div class="mt-5 articles">
            <h3 class="fw-semibold"><a href="articleDetail.php?id=<?=$categoryArticle['id'] ?>"><?= $categoryArticle['articleTitle'] ?></a></h3>
            <h5><?= $categoryArticle['articlePerex'] ?></h5>
                <?php
                $authors = $authorRepository->getAuthorArticlesForCategory($categoryArticle['articleId']);
                foreach ($authors as $author): ;?>
                        <?php
                            $formatDate = date("d.m.Y H:i", strtotime($categoryArticle['created_at']));
                        ?>
                        <a class="article-read-more" href="articleDetail.php?id= <?= $categoryArticle['id'] ?>">Číst dále <i class="fa-solid fa-arrow-right"></i></a>
                        <p><?= $formatDate ?> <a href="authorDetail.php?id=<?=$author['id'] ?>"><?= $author['authorName'] ?> <?= $author['authorSurname'] ?></a></p>
                <?php endforeach;?>
        </div>
    <?php endforeach;?>
</main>


<footer class="mt-5">
    <div class="footer-block">
        <div class="newsletter">
            <h3 class="fw-semibold mb-3">Přihlašte se k odběru novinek</h3>
            <input type="text" placeholder="Zadejte email">
            <button class="btn btn-primary">Odeslat</button>
        </div>
        <div class="footer-info">
            <div class="footer-info-item">
                <h2 class="fw-semibold">Informace</h2>
                <ul>
                    <li><a href="#">O nás</a></li>
                    <li><a href="#">Kontakt</a></li>
                    <li><a href="#">Podmínky</a></li>
                    <li><a href="#">Ochrana osobních údajů</a></li>
                </ul>
            </div>
            <div class="footer-info-item">
                <h2 class="fw-semibold">Informace</h2>
                <ul>
                    <li><a href="#">O nás</a></li>
                    <li><a href="#">Kontakt</a></li>
                    <li><a href="#">Podmínky</a></li>
                    <li><a href="#">Ochrana osobních údajů</a></li>
                </ul>
            </div>
            <div class="footer-info-item">
                <h2 class="fw-semibold">Informace</h2>
                <ul>
                    <li><a href="#">O nás</a></li>
                    <li><a href="#">Kontakt</a></li>
                    <li><a href="#">Podmínky</a></li>
                    <li><a href="#">Ochrana osobních údajů</a></li>
                </ul>
            </div>
        </div>
        <h6>©Copyright 1999-2022 by PHP News. All Rights Reserved.</h6>
    </div>
</footer>

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/a10f07ebc3.js" crossorigin="anonymous"></script>
</html>