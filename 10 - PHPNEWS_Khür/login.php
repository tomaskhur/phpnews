

<?php

    require_once 'Model/ArticleRepository.php';
    require_once 'Model/AuthorRepository.php';
    require_once  'Model/CategoryRepository.php';
    require_once 'Model/Database.php';

    session_start();

    $db = new Database();
    $ar = new ArticleRepository($db);
    $cr = new CategoryRepository($db);
    $authorRepository = new AuthorRepository($db);


    if (isset($_POST['email']) && isset($_POST['password']))
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = $authorRepository->getUserCredentials($email);
        if ($user === false) {
            header('Location: login.php?error=wrongCredentials');
            die();
        }

        if (password_verify($password, $user['password']) === FALSE)
        {
            header('Location: login.php?error=wrongCredentials');
            die();
        }
        elseif (password_verify($password, $user['password']) && $email === $user['email'])
        {
            if ($user['visible'] == 0){
                header('Location: login.php?error=notEnabled');
                die();
            }
            $_SESSION['user'] = $user;
            unset($user['password']);
            unset($user[4]);
            header('Location: administration.php');
            die();
        }
    }

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
    <title>PHP News - Přihlášení</title>
</head>
<body>

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

<main>
    <div class="admin-header-container">
        <h1 class="mt-5 fw-semibold center">Přihlášení</h1>
    </div>
    <div class="register-form">
        <form method="post">
            <input type="email" name="email" placeholder="Email">
            <input type="password" name="password" placeholder="Heslo">
            <button type="submit">Přihlásit se</button>
        </form>
        <?php
            if (isset($_GET['error']) && $_GET['error'] == 'wrongCredentials')
            {
                echo "<p class='text-danger mt-2'>Špatné heslo nebo email</p>";
            }
            elseif (isset($_GET['error']) && $_GET['error'] == 'notEnabled')
            {
                echo "<p class='text-danger mt-2'>Účet není aktivován, kontaktujte svého nadřízeného.</p>";
            }
        ?>
    </div>
</main>

</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/a10f07ebc3.js" crossorigin="anonymous"></script>
</html>