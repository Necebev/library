<?php

namespace App\Controllers;

use App\Models\WriterModel;
use App\Views\Display;

class WriterController extends Controller
{
    public function __construct(){
        $writer = new WriterModel();
        parent::__construct($writer);
    }

    public function index(){
        $writer = $this->model->all(['order_by' => ['writer'], 'direction' => ['ASC']]);
        $this->render('writers/index', ['writers' => $writer]);
    }

    public function create(){
        $this->render('writers/create');
    }

    public function edit($id){
        $writer = $this->model->find($id);
        if (!$writer){
            $_SESSION['warning_message'] = "Az író a megadott azonosítóval: $id nem található";
            $this->redirect('/writers');
        }
        $this->render('writers/edit', ['writer' => $writer]);
    }

    public function save($data){
        if (empty($data['writer']) || empty($data['biography'])){
            $_SESSION['warning_message'] = "Hiányos adatok!";
            $this->redirect('/writers');
        }

        $writers = $this->model->all();
        foreach ($writers as $writer){
            if ($writer->writer == $data['writer']){
                $_SESSION['warning_message'] = "A megadott író már szerepel!";
                $this->redirect('/writers');
            }
        }

        $this->model->writer = $data['writer'];
        $this->model->biography = $data['biography'];
        $this->model->create();
        $this->redirect('/writers');
    }

    public function update($id, $data){
        $writer = $this->model->find($id);
        if (!$writer || empty($data['writer']) || empty($data['biography'])){
            $_SESSION['warning_message'] = "Hiányos adatok!";
            $this->redirect('/writers');
        }

        $writers = $this->model->all();
        foreach ($writers as $currwriter){
            if ($id != $currwriter->id && $currwriter->writer == $data['writer']){
                $_SESSION['warning_message'] = "A megadott író már szerepel!";
                $this->redirect('/writers');
            }
        }

        $writer->writer = $data['writer'];
        $writer->biography = $data['biography'];
        $writer->update();
        $this->redirect('/writers');
    }

    function show(int $id): void
    {
        $writer = $this->model->find($id);
        if (!$writer) {
            $_SESSION['warning_message'] = "Az író a megadott azonosítóval: $id nem található.";
            $this->redirect('/writers'); // Handle invalid ID
        }
        $this->render('writers/show', ['writer' => $writer]);
    }

    function delete(int $id): void
    {
        $writer = $this->model->find($id);
        if ($writer) {
            $result = $writer->delete();
            if ($result) {
                $_SESSION['success_message'] = 'Sikeresen törölve';
            }
        }

        $this->redirect('/writers'); // Redirect regardless of success
    }
}