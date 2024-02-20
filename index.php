<?php


require_once __DIR__ . "/vendor/autoload.php";

use Controller\BooksController;
use Controller\UserController;

$booksController = new BooksController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController = new UserController;
    $userController->login();
}

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 8;
$totalBooks = $booksController->getTotalBooks();
$numberOfPages = ceil($totalBooks / $limit);

$searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$books = [];

if (!empty($searchKeyword)) {
    $books = $booksController->searchBooks($searchKeyword);
} else {
    $books = $booksController->getBooksPagination($page, $limit);
}


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bookworms Biblioteca</title>
    <link rel="stylesheet" href="./resources/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap">

</head>

<body class="custom-bd">
    <header class="my-3 mx-5 d-flex justify-content-between align-items-center">
        <a href="./index.php">
            <img src="./resources/img/bookworms.png" alt="Logo" class="logo">
        </a>
        <form action="" method="post" class="form-inline mb-3 d-flex justify-content-around align-items-center">
            <label for="user" class="me-2">Usuario:</label>
            <input class="custom-input me-2" type="text" name="user">
            <label for="password" class="me-2">Contraseña:</label>
            <input class="custom-input me-2 " type="password" name="password">
            <button class="btn btn-primary ml-2 secondary-button me-2" type="submit">Iniciar sesión</button>
        </form>
    </header>
    <h1 class="m-3 text-center my-5 first-title">BOOKWORMS</h1>
    <section class="d-flex justify-content-center">
        <form class="row g-3 m-2" action="?action=search" method="get">
            <div class="col-auto">

                <input type="text" name="keyword" class="form-control custom-input" id="inputPassword2" placeholder="Buscar">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3 secondary-button">Buscar </button>
            </div>
        </form>
    </section>

    <div class="container mt-5">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $numberOfPages; $i++) : ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
            <div class="row g-4">
            <?php if ($books) : ?>
                <?php foreach ($books as $book) : ?>
                    <div class="col-md-3">
                        <div class="card custom-card" style="width: 18rem;">
                            <img src="data:image/jpeg; base64,<?= base64_encode($book['image']) ?>" class="rounded-3 card-img-top py-3 px-5 " alt="Book Image">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <h4 class="card-title text-center custom-title"><?= $book['title'] ?></h4>
                                <p class="card-text text-center fw-bolder"><strong></strong> <?= $book['author'] ?></p>
                                <p class="card-text small text-center description"><strong></strong> <?= $book['description'] ?></p>

                            <a href="src/view/bookDetails.php?id=<?= $book['id'] ?>" class="btn btn-light rounded-3 primary-button custom-button">Ver más</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No hay libros en la base de datos.</p>
        <?php endif; ?>
    </div>
</div>


    <?php
    require_once __DIR__ . '/src/view/footer/footer.php';
    ?>