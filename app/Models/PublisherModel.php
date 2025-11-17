<?php

namespace App\Models;

class PublisherModel extends Model{
    public string|null $publisher = null;

    protected static $table = 'publishers';

    public function __construct(?string $table = null){
        parent::__construct();

        if ($table){
            $this->table = $table;
        }
    }
}