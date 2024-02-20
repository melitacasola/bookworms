<?php

use Controller\BooksController;

require_once __DIR__ . "../../../vendor/autoload.php";

$singleBook = new BooksController();

$bookId = $_GET['id'];
$bookController = new BooksController();
$book = $bookController->getBookDetails($bookId);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bookworms Biblioteca</title>
    <link rel="stylesheet" href="../../resources/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;700&display=swap">
    <link rel="stylesheet" href= "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    body {
      background-color: #F6EEE0;
    }
    </style>
  
</head>
  <body class="custom-bd">
  <header class="my-3 mx-5 d-flex justify-content-between align-items-center">
        <a href="../../index.php">
            <img src="../../resources/img/bookworms.png" alt="Logo" class="logo">
        </a>
        <div class="form-inline mb-3 d-flex justify-content-around align-items-center">
            <a href="../../index.php" class="btn btn-primary ml-2 secondary-button me-2">Back to Home</a>
        </div>
    </header>
    <main class="container">
      <article class="d-flex justify-content-center align-items-center flex-column">
        <h1 class="m-3 text-center my-5 first-title">BOOKWORMS</h1>
        <img src="data:image/jpeg;base64,<?=base64_encode($book['image']) ?>" alt="Book Image" class="img-fluid w-20 h-40 m-4">
        <h3 class="custom-blue-text mt-4"><?= $book["title"];?></h3>
        <h4 class="custom-blue-text mt-3"><?= $book["author"];?></h4>
        <p class="paragraph-text mt-3 text-center" style="width: 50%;"><?= $book["description"];?></p>
      </article>
    </main>
  
    <?php
    require_once __DIR__ . '/footer/footer.php';
    ?>



