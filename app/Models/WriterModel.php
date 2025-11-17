<?php

namespace App\Models;

class WriterModel extends Model{
    public string|null $writer = null;
    public string|null $biography = null;

    protected static $table = 'writers';

    public function __construct(?string $writer = null, ?string $biography = null){
        parent::__construct();

        if ($writer){
            $this->writer = $writer;
        }

        if ($biography){
            $this->biography = $biography;
        }
    }
}