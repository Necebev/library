<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Views\Display;

class CategoryController extends Controller
{
    public function __construct(){
        $category = new CategoryModel();
        parent::__construct($category);
    }

    public function index(){
        $category = $this->model->all(['order_by' => ['category'], 'direction' => ['ASC']]);
        $this->render('categories/index', ['categories' => $category]);
    }

    public function create(){
        $this->render('categories/create');
    }

    public function edit($id){
        $category = $this->model->find($id);
        if (!$category){
            $_SESSION['warning_message'] = "A kategória a megadott azonosítóval: $id nem található";
            $this->redirect('/categories');
        }
        $this->render('categories/edit', ['category' => $category]);
    }

    public function save($data){
        if (empty($data['category'])){
            $_SESSION['warning_message'] = "Hiányos adat!";
            $this->redirect('/categories');
        }

        $categories = $this->model->all();
        foreach ($categories as $category){
            if ($category->category == $data['category']){
                $_SESSION['warning_message'] = "A megadott kategória már szerepel!";
                $this->redirect('/categories');
            }
        }

        $this->model->category = $data['category'];
        $this->model->create();
        $this->redirect('/categories');
    }

    public function update($id, $data){
        $category = $this->model->find($id);
        if (!$category || empty($data['category'])){
            $_SESSION['warning_message'] = "Hiányos adat!";
            $this->redirect('/categories');
        }

        $categories = $this->model->all();
        foreach ($categories as $currcategory){
            if ($id != $currcategory->id && $currcategory->category == $data['category']){
                $_SESSION['warning_message'] = "A megadott kategória már szerepel!";
                $this->redirect('/categories');
            }
        }

        $category->category = $data['category'];
        $category->update();
        $this->redirect('/categories');
    }

    function show(int $id): void
    {
        $category = $this->model->find($id);
        if (!$category) {
            $_SESSION['warning_message'] = "A kategória a megadott azonosítóval: $id nem található.";
            $this->redirect('/categories'); // Handle invalid ID
        }
        $this->render('categories/show', ['category' => $category]);
    }

    function delete(int $id): void
    {
        $category = $this->model->find($id);
        if ($category) {
            $result = $category->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/categories'); // Redirect regardless of success
    }
}