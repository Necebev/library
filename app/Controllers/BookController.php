<?php

namespace App\Controllers;

use App\Models\BookModel;
use App\Views\Display;

class BookController extends Controller
{
    public function __construct(){
        $book = new BookModel();
        parent::__construct($book);
    }

    public function index($table = "books", $attribute = "title"){
        $book = $this->model->getBooksBy($table, $attribute); // parameters: table, attribute, orderBy (asc or desc)
        $this->render('books/index', ['books' => $book]);
    }

    public function search($table = "books", $attribute = "title", $text){
        
        $book = $this->model->searchBooks($table, $attribute, $text);
        $this->render('books/index', ['books' => $book]);
    }

    public function saveRating($book_id, $rating){
        $this->model->saveBookRating($book_id, $rating);
    }

    public function create(){
        $this->render('books/create');
    }

    public function edit($id){
        $book = $this->model->find($id);
        if (!$book){
            $_SESSION['warning_message'] = "A könyv a megadott azonosítóval: $id nem található";
            $this->redirect('/');
        }
        $this->render('books/edit', ['book' => $book]);
    }

    public function save($data){
        if (empty($data['title']) || (empty($data['ISBN']) && $data['ISBN'] != 0) || (empty($data['price']) && $data['price'] != 0) || empty($data['content'])){
            $_SESSION['warning_message'] = "Hiányos adatok!";
            $this->redirect('/');
        }

        if (!is_numeric($data['ISBN']) || !is_numeric($data['price']) || is_numeric($data['content'])){
            $_SESSION['warning_message'] = "Nem megfelelő adatok!";
            $this->redirect('/');
        }

        $books = $this->model->all();
        foreach ($books as $book){
            if ($book->title == $data['title']){
                $_SESSION['warning_message'] = "A megadott könyv már szerepel!";
                $this->redirect('/');
            }
        }

        $this->model->writer_id = $data[''];
        $this->model->publisher_id = $data['publisher_id'];
        $this->model->category_id = $data['category_id'];
        $this->model->title = $data['title'];

        // handle file upload
        $fileName = "../files/" . $_FILES['cover']['name'];
        if (move_uploaded_file($_FILES['cover']['tmp_name'], $fileName)){
            $newFileName = str_replace(' ', '_', str_replace('\\', '/', $fileName));
            rename(realpath($fileName), $newFileName);
            $this->model->cover = "LOAD_FILE('" . str_replace('\\', '/', realpath($newFileName)) . "')";
        }

        $this->model->ISBN = $data['ISBN'];
        $this->model->price = $data['price'];
        $this->model->content = $data['content'];
        $this->model->create();
        $this->redirect('/');
    }

    public function update($id, $data){
        $book = $this->model->find($id);

        if (!$book || empty($data['title']) || (empty($data['ISBN']) && $data['ISBN'] != 0) || (empty($data['price']) && $data['price'] != 0) || empty($data['content'])){
            $_SESSION['warning_message'] = "Hiányos adatok!";
            $this->redirect('/');
        }

        if (!is_numeric($data['ISBN']) || !is_numeric($data['price']) || is_numeric($data['content'])){
            $_SESSION['warning_message'] = "Nem megfelelő adatok!";
            $this->redirect('/');
        }

        $books = $this->model->all();
        foreach ($books as $currbook){
            if ($id != $currbook->id && $currbook->title == $data['title']){
                $_SESSION['warning_message'] = "A megadott könyv már szerepel!";
                $this->redirect('/');
            }
        }

        $book->writer_id = $data['writer_id'];
        $book->publisher_id = $data['publisher_id'];
        $book->category_id = $data['category_id'];
        $book->title = $data['title'];

        // handle file upload
        $fileName = "../files/" . $_FILES['cover']['name'];
        if (move_uploaded_file($_FILES['cover']['tmp_name'], $fileName)){
            $newFileName = str_replace(' ', '_', str_replace('\\', '/', $fileName));
            rename(realpath($fileName), $newFileName);
            $book->cover = "LOAD_FILE('" . str_replace('\\', '/', realpath($newFileName)) . "')";
        }

        $book->ISBN = $data['ISBN'];
        $book->price = $data['price'];
        $book->content = $data['content'];
        $book->update();
        $this->redirect('/');
    }

    function show(int $id): void
    {
        $book = $this->model->find($id);
        if (!$book) {
            $_SESSION['warning_message'] = "A könyv a megadott azonosítóval: $id nem található.";
            $this->redirect('/'); // Handle invalid ID
        }
        $this->render('books/show', ['book' => $book]);
    }

    function delete(int $id): void
    {
        $book = $this->model->find($id);
        if ($book) {
            $result = $book->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/'); // Redirect regardless of success
    }
}