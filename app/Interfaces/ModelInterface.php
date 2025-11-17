<?php

namespace App\Interfaces;

interface ModelInterface{
    function find(int $id);
    function all(array $orderby = []): array;
    function delete();
    public function create();
    public function update();
}