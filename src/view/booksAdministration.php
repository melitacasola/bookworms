<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use Controller\BooksController;
use Controller\UserController;

session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit();
}

if (isset($_POST['logout'])) {
    $userController = new UserController;
    $userController->logout();
}

$booksController = new BooksController();
$bookId =  $_GET['id'] ?? null;

$books = $booksController->getBooks();
$bookDetails = null;

if ($bookId !== null) {
    $bookDetails = $booksController->getBookDetails($bookId);
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && $bookId !== null) {
    $result = $booksController->deleteBook($bookId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booksController->handleFormSubmission(); 

    unset($_SESSION['isbn'], $_SESSION['title'], $_SESSION['author'], $_SESSION['image'], $_SESSION['description']);
    
}


    $searchKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    $books = $booksController->searchBooks($searchKeyword);
?>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookworms Biblioteca Admin</title>

    <link rel="stylesheet" href="../../resources/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .form-control {
            display: grid !important;
        }
    </style>
</head>

<body class="custom-bd">
    <header class="my-3 mx-5 d-flex justify-content-between align-items-center">
        <a href="../../index.php">
            <img src="../../resources/img/bookworms.png" alt="Logo" class="logo">
        </a>
        <form action="" method="post">
            <input type="submit" name="logout" value="Cerrar Sesion" class="btn btn-primary mb-3 secondary-button">
        </form>
    </header>

    <main>
        <h1 class="m-3 text-center my-5 first-title">BOOKWORMS</h1>
        
        <div class="row">
            <div class="d-flex justify-content-center ">
                <?php if (isset($_SESSION['error'])) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><?php echo $_SESSION['error']; ?></<strong>
                    <?php unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
            </div>
        </div>
        <?php var_dump($_SESSION) ; ?>
        
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <div class="d-flex justify-content-around">
                <div class="left-column">
                    <input type="hidden" name="bookId" value="<?= ($bookDetails !== null) ? $bookDetails['id'] : '' ?>">

                    <div class="mb-2">
                        <label for="title">Titulo:
                            <input class="form-control custom-input-form mb-2" type="text" name="title" value="<?= (isset($_SESSION['title'])) ? $_SESSION['title'] : (($bookDetails !== null) ? $bookDetails['title'] : '') ?>">

                        </label>
                    </div>
                    <div class="mb-2">
                        <label for="author">Autor:
                            <input type="text" name="author" value="<?= (isset($_SESSION['author'])) ? $_SESSION['author'] : (($bookDetails !== null) ? $bookDetails['author'] : '') ?>" class="custom-input-form form-control mb-2">
                        </label>
                    </div>
                    <div class="mb-2">
                        <label for="isbn">ISBN:
                            <input type="text" name="isbn" value="<?= (isset($_SESSION['isbn'])) ? $_SESSION['isbn'] : (($bookDetails !== null) ? $bookDetails['isbn'] : '') ?>" class="custom-input-form form-control mb-2" />
                        </label>
                    </div>
                </div>
                <div class="right-column">
                    <div class="mb-2">
                        <label for="image" class="custom-input-image">Imagen:
                            <input type="file" name="image" class="mb-2 form-control custom-input-image " value="<?= (isset($_SESSION['image'])) ? $_SESSION['image'] : ''  ?>" />
                        </label>
                    </div>
                    <div class="mb-2">
                        <label for="description">Descripción:
                            <textarea name="description" class="custom-input-form form-control mb-2"><?= (isset($_SESSION['description'])) ? $_SESSION['description'] : (($bookDetails !== null) ? $bookDetails['description'] : '')?></textarea>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-12 text-center mt-4">
                <button type="submit" name="<?= ($bookId !== null) ? 'editBookSubmit' : 'addBook' ?>" class="btn btn-primary mb-3 custom-button primary-button"><?= ($bookId !== null) ? 'Guardar Cambios' : 'Añadir' ?></button>
            </div>
        </form>

        <section class="d-flex justify-content-center">
            <form class="row g-3 m-2" action="?action=search" method="get">
                <div class="col-auto">
                    <input type="text" name="keyword" class="form-control custom-input" placeholder="Buscar">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary mb-3 secondary-button">Buscar </button>
                </div>
            </form>
        </section>

        <section>
            <div class="container mt-5">
                <div class="row g-4">
                    <?php if ($books) : ?>
                        <?php foreach ($books as $book) : ?>
                            <div class="col-md-3 mb-4">
                                <div class="card custom-card" style="width: 18rem;">
                                    <img src="data:image/jpeg; base64,<?= base64_encode($book['image']) ?>" class="rounded-3 card-img-top py-3 px-5 " alt="Book Image">
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        <h4 class="card-title text-center"><?= $book['title'] ?></h4>
                                        <p class="card-text text-center fw-bolder"><strong></strong> <?= $book['author'] ?></p>
                                        <p class="card-text small text-center description"><strong></strong> <?= $book['description'] ?></p>
                                        <div class="d-flex justify-content-between align-items-center w-90">
                                            <a class="btn btn-danger rounded-4 mx-1 py-2 px-3" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $book['id'] ?>"><i class="fa fa-trash"></i> Eliminar</a>
                                            <a href="../view/booksAdministration.php?id=<?= $book['id'] ?>" class="secondary-button-book mx-1 py-2 px-3"><i class="fa fa-edit"></i> Editar</a>
                                        </div>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal<?= $book['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">¿Desea eliminar el Libro <?= $book['title'] ?> de <?= $book['author'] ?> ?</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Una vez eliminado no se podrá recuperar el registro
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cerrar</button>
                                                        <a href="../view/booksAdministration.php?action=delete&id=<?= $book['id'] ?>" class="btn btn-danger">Eliminar</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p class="col-12"><?php echo $error ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <?php
    require_once __DIR__ . '/footer/footer.php';
    ?>
