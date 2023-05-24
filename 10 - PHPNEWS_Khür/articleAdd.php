<?php
    require_once 'Model/ArticleRepository.php';
    require_once 'Model/AuthorRepository.php';
    require_once  'Model/CategoryRepository.php';
    require_once 'Model/Database.php';
    require_once 'login_check.php';

    $db = new Database();
    $ar = new ArticleRepository($db);
    $cr = new CategoryRepository($db);
    $authorRepository = new AuthorRepository($db);

    if (isset($_GET['id']))
    {
        $articleId = $_GET['id'];


        $articleCategory = $cr->getArticleCategoryForCheckbox($articleId);
        $articleCategoryIds = array_map(function ($ac) {
            return $ac['category_id'];
        }, $articleCategory);
    }
?>

<?php
    if (isset($_POST) && !empty(($_POST)))
    {
        if (isset($articleId))
        {
            $uploadDirectory = "images/";
            $fileName = $_FILES['image']['name'];

            $uploadPath = $uploadDirectory . basename($fileName);
            $didUpload = move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);

            $ar->updateArticle($articleId, $_POST['title'], $_POST['perex'], $_POST['text'], $_POST['visible'], $uploadPath, $_POST['author_id']);

            $ar->deleteAllCategoriesFromArticle($articleId);
            foreach ($_POST['category'] as $category_id)
            {
                $cr->addCategoryToArticle($articleId, $category_id);
            }
            header('Location: administration.php');
        }
        else
        {

            $uploadDirectory = "images/";
            $fileName = $_FILES['image']['name']; //dostanu jméno souboru

            $uploadPath = $uploadDirectory . basename($fileName);
            $didUpload = move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);

            $article_id = $ar->addArticle($_POST['title'], $_POST['perex'], $_POST['text'], $_POST['visible'], $uploadPath, $_POST['author_id']);

            foreach ($_POST['category'] as $category_id)
            {
                $cr->addCategoryToArticle($article_id, $category_id);
            }
            header('Location: administration.php');
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
    <title>PHP NEWS - Přidat článek</title>
</head>
<body>

<script src="Plugins/tinymce_6.2.0/tinymce/js/tinymce/tinymce.min.js"> referrerpolicy="origin"></script>
<script>tinymce.init({selector:'#article-textarea',
                      content_style: "body { margin-bottom: 2rem;}",
                      plugins: 'link',
});</script>

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

<form method="POST" enctype="multipart/form-data">
    <div class="form-container">
        <?php
            if (isset($_GET['id']))
            {
                $articleId = $_GET['id'];
                $article = $ar->getArticle($articleId);
            }
        ?>

        <h1 class="mt-5 fw-semibold"><?= isset($_GET['id']) ? 'Aktualizujte článek' : 'Přidejte článek' ?></h1>
        <input class="mb-3 mt-5" type="text" name="title" value="<?= isset($articleId) ? $article['title'] : '' ?>" placeholder="Název článku...">
        <textarea class="mt-3 mb-1" id="textarea-perex" type="text" name="perex" placeholder="Perex..."><?= isset($articleId) ? $article['perex'] : '' ?></textarea>

        <div class="word-count mb-3 mt-3">
            <span id="perexWordCount">0</span> Znaků
        </div>

        <textarea class="mb-5" name="text" id="article-textarea"><?= isset($articleId) ? $article['text'] : '' ?></textarea>
        <div class="checkbox-dropdown mt-5">
            Vyberte kategorii:

            <ul class="checkbox-dropdown-list">
                <?php foreach($categories as $category): ?>
                    <li>
                        <label>
                            <input type="checkbox" name="category[]" value="<?= $category['id'] ?>"
                        <?php if (isset($articleId)): ?>
                            <?php if (in_array($category['id'], $articleCategoryIds)): ?>
                                checked
                            <?php endif; ?>
                        <?php endif;?>
                        >
                         <?= $category['name'] ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
        <select name="author_id" class="category-selection">
            <option value="#" selected hidden>Vyberte autora:</option>
                <?php foreach ($authors as $author): ?>
                    <option <?= isset($articleId) && $article['author_id'] == $author['id'] ? 'selected' : '';?> value="<?= $author['id'] ?>"><?= $author['name'] . ' ' . $author['surname'] ?></option>
                <?php endforeach; ?>
            </select>
        <input type="hidden" name="visible" value="0">
        <input type="checkbox" name="visible" value="1" <?= isset($articleId) && $article['visible'] == 1 ? 'checked' : '' ?>> Veřejný článek
        <input type="file" name="image" accept="image/*" placeholder="Vyberte obrázek k článku" class="mt-5">
        <input class="mb-5 fw-semibold" type="submit" name="submit" value="<?= isset($articleId) ? 'Upravit článek' : 'Přidat nový článek' ?>">
    </div>
</form>

<!--<footer class="mt-5">-->
<!--    <div class="footer-block">-->
<!--        <h6>©Copyright 1999-2022 by PHP News. All Rights Reserved.</h6>-->
<!--    </div>-->
<!--</footer>-->

</body>
<script>
    var textboxPerex = document.getElementById('textarea-perex');
    var perexWordCount = document.getElementById('perexWordCount');

    textboxPerex.addEventListener('keyup', function() {
        var characters = textboxPerex.value.split('');
        perexWordCount.innerText = characters.length;
    });
</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/a10f07ebc3.js" crossorigin="anonymous"></script>

<script src="Plugins/script.js"></script>
</html>