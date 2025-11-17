<?php

namespace App\Routing;

use App\Controllers\BookController;
use App\Controllers\WriterController;
use App\Controllers\CategoryController;
use App\Controllers\PublisherController;
use App\Views\Display;

class Router{
    public function handle(){
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $requestUri = $_SERVER['REQUEST_URI'];

        if ($method === 'POST' && isset($_POST['_method'])){
            $method = strtoupper($_POST['_method']);
        }

        $this->dispatch($method, $requestUri);
    }

    private function dispatch($method, $requestUri){
        switch ($method){
            case 'GET':
                $this->handleGetRequests($requestUri);
                break;
            case 'POST':
                $this->handlePostRequests($requestUri);
                break;
            case 'PATCH':
                $this->handlePatchRequests($requestUri);
                break;
            case 'DELETE':
                $this->handleDeleteRequests($requestUri);
                break;
            case 'RATE':
                $this->handleRateRequests($requestUri);
                break;
            case 'LIST':
                $this->handleListRequests($requestUri);
                break;
            case 'SEARCH':
                $this->handleSearchRequests($requestUri);
                break;
            default:
                $this->methodNotAllowed();
                break;
        }
    }

    private function handleGetRequests($requestUri){
        switch ($requestUri){
            case '/':
                $bookController = new BookController();
                $bookController->index();
                break;
            case '/writers':
                $writerController = new WriterController();
                $writerController->index();
                break;
            case '/publishers':
                $publisherController = new PublisherController();
                $publisherController->index();
                break;
            case '/categories':
                $categoryController = new CategoryController();
                $categoryController->index();
                break;
            default:
                $this->notFound();
        }
    }

    private function handlePostRequests($requestUri){
        $data = $this->filterPostData($_POST);
        $id = $data['id'] ?? null;

        switch ($requestUri){
            case '/':
                if (!empty($data)){
                    $bookController = new BookController();
                    $bookController->save($data);
                }
                break;
            case '/create':
                $bookController = new BookController();
                $bookController->create();
                break;
            case '/edit':
                $bookController = new BookController();
                $bookController->edit($id);
                break;
            case '/writers':
                if (!empty($data)){
                    $writerController = new WriterController();
                    $writerController->save($data);
                }
                break;
            case '/writers/create':
                $writerController = new WriterController();
                $writerController->create();
                break;
            case '/writers/edit':
                $writerController = new WriterController();
                $writerController->edit($id);
                break;
            case '/publishers':
                if (!empty($data)){
                    $publisherController = new PublisherController();
                    $publisherController->save($data);
                }
                break;
            case '/publishers/create':
                $publisherController = new PublisherController();
                $publisherController->create();
                break;
            case '/publishers/edit':
                $publisherController = new PublisherController();
                $publisherController->edit($id);
                break;
            case '/categories':
                if (!empty($data)){
                    $categoryController = new CategoryController();
                    $categoryController->save($data);
                }
                break;
            case '/categories/create':
                $categoryController = new CategoryController();
                $categoryController->create();
                break;
            case '/categories/edit':
                $categoryController = new CategoryController();
                $categoryController->edit($id);
                break;
            default:
                $this->notFound();
        }
    }

    private function handlePatchRequests($requestUri){
        $data = $this->filterPostData($_POST);
        $id = $data['id'] ?? null;

        switch ($requestUri){
            case '/':
                $bookController = new BookController();
                $bookController->update($id, $data);
                break;
            case '/writers':
                $writerController = new WriterController();
                $writerController->update($id, $data);
                break;
            case '/publishers':
                $publisherController = new PublisherController();
                $publisherController->update($id, $data);
                break;
            case '/categories':
                $categoryController = new CategoryController();
                $categoryController->update($id, $data);
                break;
            default:
                $this->notFound();
        }
    }

    private function handleDeleteRequests($requestUri){
        $data = $this->filterPostData($_POST);

        switch ($requestUri){
            case '/':
                $bookController = new BookController();
                $bookController->delete($data['id']);
                break;
            case '/writers':
                $writerController = new WriterController();
                $writerController->delete($data['id']);
                break;
            case '/publishers':
                $publisherController = new PublisherController();
                $publisherController->delete($data['id']);
                break;
            case '/categories':
                $categoryController = new CategoryController();
                $categoryController->delete($data['id']);
                break;
            default:
                $this->notFound();
        }
    }

    private function handleRateRequests($requestUri){
        $data = $this->filterPostData($_POST);
        $bookController = new BookController();

        if (!empty($data['starsValue'])){
            $bookController->saveRating($data['id'], $data['starsValue']);
        }

        $bookController->index();
    }

    private function handleListRequests($requestUri){
        $data = $this->filterPostData($_POST);

        $bookController = new BookController();
        $bookController->index($data['table'], $data['attribute']);
    }

    private function handleSearchRequests($requestUri){
        $data = $this->filterPostData($_POST);

        $bookController = new BookController();
        $bookController->search($data['searchByTable'], $data['searchByAttribute'], $data['text']);
    }

    private function filterPostData(array $data): array
    {
        // Remove unnecessary keys in a clean and simple way
        $filterKeys = ['_method', 'submit', 'btn-del', 'btn-save', 'btn-edit', 'btn-plus', 'btn-update'];
        return array_diff_key($data, array_flip($filterKeys));
    }

    private function notFound(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        Display::message("404 Not Found", 'error');
    }

    private function methodNotAllowed(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        Display::message("405 Method Not Allowed", 'error');
    }
}