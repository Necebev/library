<?php

namespace App\Models;

class CategoryModel extends Model{
    public string|null $category = null;

    protected static $table = 'categories';

    public function __construct(?string $table = null){
        parent::__construct();

        if ($table){
            $this->table = $table;
        }
    }
}