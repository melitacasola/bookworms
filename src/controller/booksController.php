<?php

namespace Controller;

use Model\booksModel;

class BooksController
{
    private $model;

    public function __construct()
    {
        $this->model = new booksModel;
    }

    public function getBooks()
    {
        return ($this->model->getBooks()) ? $this->model->getBooks() : 'No hay libros en la base de datos';
    }

    public function getTotalBooks(){
        return ($this ->model->getTotalBooks()  > 0) ? $this->model->getTotalBooks() : 'No hay libros en la base de datos';
    }

    public function getBooksPagination($page, $limit)
    {
        return ($this->model->getBooksPagination($page, $limit)) ? $this->model->getBooksPagination($page, $limit) : 'No hay libros en la base de datos';
    }

    public function getBookDetails($id)
    {
        return ($this->model->getBookDetails($id) != false) ? $this->model->getBookDetails($id) : 'El libro no existe';
    }

    public function searchBooks($keyword)
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        return  $this->model->searchBooks($keyword);
    }

    public function addBook($isbn, $title, $author, $image, $description)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addBook'])) {

            if (!empty($isbn) && !empty($title) && !empty($author) && !empty($image) && !empty($description)) {
                $result = $this->model->addBook($isbn, $title, $author, $image, $description);
                $this->handleSuccess("Los datos fueron agregados correctamente.");

                // unset($_SESSION['isbn'], $_SESSION['title'], $_SESSION['author'], $_SESSION['image'], $_SESSION['description']);
                return header("Location: ../view/booksAdministration.php");
            } else {
                return 'Error al crear el libro';
            }
        }
    }

    public function deleteBook($id)
    {
        return ($this->model->deleteBook($id)) ? header("Location: ../view/booksAdministration.php") : 'error eliminar libro';
    }


    public function editBook($id, $isbn, $title, $author, $image, $description)
    {
        $result = $this->model->editBook($id, $isbn, $title, $author, $image, $description);

        $this->handleSuccess("Los datos fueron editados correctamente.");
        return ($result) ?  header("Location: ../view/booksAdministration.php"): 'error al Editar libro';
    }

    public function handleErrors($errorMessage)
    {
        $_SESSION['error'] = $errorMessage;
        header('Location: ../view/booksAdministration.php');
        exit();
    }

    public function handleSuccess($successMessage)
    {
        $_SESSION['success'] = $successMessage;
        header('Location: ../view/booksAdministration.php');
        exit();
    }

    public function handleFormSubmission()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST["addBook"])) {
                if (empty($_POST["isbn"]) || empty($_POST["title"]) || empty($_POST["author"]) || empty($_FILES["image"]["tmp_name"]) || empty($_POST["description"])) {

                    // $_SESSION['isbn'] = $_POST["isbn"];
                    // $_SESSION['title'] = $_POST["title"];
                    // $_SESSION['author'] = $_POST["author"];
                    // $_SESSION['image'] = $_FILES["image"]["tmp_name"];
                    // $_SESSION['description'] = $_POST["description"];

                    $this->handleErrors("Error! Todos los campos son obligatorios.");
                    // unset($_SESSION['isbn'], $_SESSION['title'], $_SESSION['author'], $_SESSION['image'], $_SESSION['description']);

                } else {
                    $isbn = $_POST["isbn"];
                    $title = $_POST["title"];
                    $author = $_POST["author"];
                    $image = file_get_contents($_FILES["image"]["tmp_name"]);
                    $description = $_POST["description"];

                    
                    if ($image === false) {
                        $this->handleErrors("Error al cargar la imagen.");
                    }

                    $this->addBook($isbn, $title, $author, $image, $description);

                    unset($_SESSION['isbn'], $_SESSION['title'], $_SESSION['author'], $_SESSION['image'], $_SESSION['description']);

                    exit();
                }
            } elseif (isset($_POST['editBookSubmit'])) {
                $isbn = $_POST['isbn'];
                $title = $_POST['title'];
                $author = $_POST['author'];
                $description = $_POST['description'];

                
                if (!empty($_FILES["image"]["tmp_name"])) {
                    $image = file_get_contents($_FILES["image"]["tmp_name"]);

                    
                    if ($image === false) {
                        $this->handleErrors("Error al cargar la imagen.");
                    }
                } else {
                    $image = ''; 
                }

                $bookId = $_POST['bookId'];

                $this->editBook($bookId, $isbn, $title, $author, $image, $description);
            }
        }
    }


}
