<?php

namespace App\Models;

class BookModel extends Model{
    public int|null $writer_id = null;
    public int|null $publisher_id = null;
    public int|null $category_id = null;
    public string|null $title = null;
    public string|null $cover = null;
    public string|null $ISBN = null;
    public int|null $price = null;
    public string|null $content = null; 

    protected static $table = 'books';

    public function __construct(?int $writer_id = null, ?int $publisher_id = null, ?int $category_id = null, ?string $title = null, ?string $cover = null, ?string $ISBN = null, ?int $price = null, ?string $content = null){
        parent::__construct();

        if ($writer_id){
            $this->writer_id = $writer_id;
        }

        if ($publisher_id){
            $this->publisher_id = $publisher_id;
        }

        if ($category_id){
            $this->category_id = $category_id;
        }

        if ($title){
            $this->title = $title;
        }

        if ($cover){
            $this->cover = $cover;
        }

        if ($ISBN){
            $this->ISBN = $ISBN;
        }

        if ($price){
            $this->price = $price;
        }

        if ($content){
            $this->content = $content;
        }
    }

    public function getWriter(){
        $writer = new WriterModel();
        return $writer->find($this->writer_id);
    }

    public function getPublisher(){
        $publisher = new PublisherModel();
        return $publisher->find($this->publisher_id);
    }

    public function getCategory(){
        $category = new CategoryModel();
        return $category->find($this->category_id);
    }

    public function getWriters($id = ""){
        $writer = new WriterModel();
        $writers = $writer->all(['order_by' => ['writer']]);
        $options = "";
        for ($i = 0; $i < count($writers); $i++){
            $options .= '<option value="' . $writers[$i]->id . '"';
            if ($writers[$i]->id == $id){
                $options .= ' selected';
            }
            $options .= '>' . $writers[$i]->writer . '</option>';
        }
        return $options;
    }

    public function getPublishers($id = ""){
        $publisher = new PublisherModel();
        $publishers = $publisher->all(['order_by' => ['publisher']]);
        $options = "";
        for ($i = 0; $i < count($publishers); $i++){
            $options .= '<option value="' . $publishers[$i]->id . '"';
            if ($publishers[$i]->id == $id){
                $options .= ' selected';
            }
            $options .= '>' . $publishers[$i]->publisher . '</option>';
        }
        return $options;
    }

    public function getCategories($id = ""){
        $category = new CategoryModel();
        $categories = $category->all(['order_by' => ['category']]);
        $options = "";
        for ($i = 0; $i < count($categories); $i++){
            $options .= '<option value="' . $categories[$i]->id . '"';
            if ($categories[$i]->id == $id){
                $options .= ' selected';
            }
            $options .= '>' . $categories[$i]->category . '</option>';
        }
        return $options;
    }
}