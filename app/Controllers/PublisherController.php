<?php

namespace App\Controllers;

use App\Models\PublisherModel;
use App\Views\Display;

class PublisherController extends Controller
{
    public function __construct(){
        $publisher = new PublisherModel();
        parent::__construct($publisher);
    }

    public function index(){
        $publisher = $this->model->all(['order_by' => ['publisher'], 'direction' => ['ASC']]);
        $this->render('publishers/index', ['publishers' => $publisher]);
    }

    public function create(){
        $this->render('publishers/create');
    }

    public function edit($id){
        $publisher = $this->model->find($id);
        if (!$publisher){
            $_SESSION['warning_message'] = "A kiadó a megadott azonosítóval: $id nem található";
            $this->redirect('/publishers');
        }
        $this->render('publishers/edit', ['publisher' => $publisher]);
    }

    public function save($data){
        if (empty($data['publisher'])){
            $_SESSION['warning_message'] = "Hiányos adat!";
            $this->redirect('/publishers');
        }

        $publishers = $this->model->all();
        foreach ($publishers as $publisher){
            if ($publisher->publisher == $data['publisher']){
                $_SESSION['warning_message'] = "A megadott kiadó már szerepel!";
                $this->redirect('/publishers');
            }
        }

        $this->model->publisher = $data['publisher'];
        $this->model->create();
        $this->redirect('/publishers');
    }

    public function update($id, $data){
        $publisher = $this->model->find($id);
        if (!$publisher || empty($data['publisher'])){
            $_SESSION['warning_message'] = "Hiányos adat!";
            $this->redirect('/publishers');
        }

        $publishers = $this->model->all();
        foreach ($publishers as $currpublisher){
            if ($id != $currpublisher->id && $currpublisher->publisher == $data['publisher']){
                $_SESSION['warning_message'] = "A megadott kiadó már szerepel!";
                $this->redirect('/publishers');
            }
        }

        $publisher->publisher = $data['publisher'];
        $publisher->update();
        $this->redirect('/publishers');
    }

    function show(int $id): void
    {
        $publisher = $this->model->find($id);
        if (!$publisher) {
            $_SESSION['warning_message'] = "A kiadó a megadott azonosítóval: $id nem található.";
            $this->redirect('/publishers'); // Handle invalid ID
        }
        $this->render('publishers/show', ['publisher' => $publisher]);
    }

    function delete(int $id): void
    {
        $publisher = $this->model->find($id);
        if ($publisher) {
            $result = $publisher->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/publishers'); // Redirect regardless of success
    }
}