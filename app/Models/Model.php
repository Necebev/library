<?php

namespace App\Models;

use App\Database\Database;
use App\Database\Install;
use App\Interfaces\ModelInterface;

abstract class Model implements ModelInterface
{
    public int $id;

    protected $db;

    protected static $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    function mapToModel(array $data): Model {
        $model = new static(); // Creates an instance of the current model
        foreach ($data as $key => $value) {
            if (property_exists($model, $key)) {
                $model->$key = $value;
            }
        }

        return $model;
    }

    static function select(): string
    {
        return "SELECT * FROM `" . static::$table . "` ";
    }

    static function orderBy($orderBy = []): string
    {
        if (empty($orderBy)) {
            return "";
        }

        $orderByClauses = [];

        // Extract 'orderBy' and 'direction' fields
        $fields = $orderBy['order_by'] ?? [];
        $directions = $orderBy['direction'] ?? [];

        foreach ($fields as $index => $field) {
            // Use the corresponding direction or default to 'ASC'
            $direction = $directions[$index] ?? 'ASC';
            $orderByClauses[] = "$field $direction";
        }

        if (empty($orderByClauses)) {
            return "";
        }

        return " ORDER BY " . implode(', ', $orderByClauses) . ";";
    }

    function getBookReview($book_id){
        $sql = "SELECT rating FROM `ratings` WHERE book_id = $book_id";

        $qryResult = $this->db->execSql($sql);
        if (empty($qryResult)){
            return [];
        }

        return $qryResult;
    }

    function saveBookRating($book_id, $rating){
        $sql = "INSERT INTO `ratings`(book_id, rating) VALUES($book_id, $rating)";
        $this->db->execSql($sql);
    }

    function searchBooks($table, $attribute, $text){
        $attributes = ["books.id", "writer_id", "publisher_id", "category_id", "title", "cover", "ISBN", "price", "content"];
        $attributesOut = implode(",", $attributes);

        $sql = "SELECT $attributesOut FROM `books`
        JOIN writers ON writers.id = books.writer_id
        JOIN publishers ON publishers.id = books.publisher_id
        JOIN categories ON categories.id = books.category_id
        WHERE `$table`.$attribute LIKE '%$text%'";

        $qryResult = $this->db->execSql($sql);
        if (empty($qryResult)){
            return [];
        }

        $results = [];
        foreach ($qryResult as $row){
            $results[] = $this->mapToModel($row);
        }

        return $results;
    }

    function getBooksBy($table, $attribute, $orderBy = "ASC"){
        $attributes = ["books.id", "writer_id", "publisher_id", "category_id", "title", "cover", "ISBN", "price", "content"];
        $attributesOut = implode(",", $attributes);
        $sql = "SELECT $attributesOut FROM `books`
        JOIN writers ON writers.id = books.writer_id
        JOIN publishers ON publishers.id = books.publisher_id
        JOIN categories ON categories.id = books.category_id
        ORDER BY `$table`.$attribute $orderBy";

        $qryResult = $this->db->execSql($sql);
        if (empty($qryResult)){
            return [];
        }

        $results = [];
        foreach ($qryResult as $row){
            $results[] = $this->mapToModel($row);
        }

        return $results;
    }

    function find(int $id): ?static
    {
        $sql = self::select() . " WHERE id = :id";

        $qryResult = $this->db->execSql($sql, ['id' => $id]);
        if (empty($qryResult)) {
            return null;
        }

        return $this->mapToModel($qryResult[0]);
    }

    function all($orderBy = []): array
    {
        $sql = self::select();

        $sql .= self::orderBy($orderBy);

        $qryResult = $this->db->execSql($sql);

        if (empty($qryResult)) {
            return [];
        }

        $results = [];
        foreach ($qryResult as $row) {
            $results[] = $this->mapToModel($row);
        }

        return $results;
    }

    function delete()
    {
        $sql = "DELETE FROM `" . static::$table . "` WHERE id = :id";

        return $this->db->execSql($sql, ['id' => $this->id]);
    }

    public function create()
    {
        $properties = get_object_vars($this);
        // Exclude 'id', it is auto-incremented
        unset($properties['id']);
        unset($properties['db']);
        unset($properties['table']);

        $filePath = "";
        
        $columns = implode(', ', array_keys($properties));
        
        $placeholders = [];
        foreach (array_keys($properties) as $key) {
            if ($key == 'cover'){
                if ($properties[$key] == ''){
                    $_SESSION['warning_message'] = 'Nincs fájl kiválasztva...';
                    return false;
                }
                $placeholders[] = $properties[$key];
                $filePath = $properties[$key];
                unset($properties[$key]);
            }
            else{
                $placeholders[] = ":$key";
            }
        }
        $placeholders = implode(', ', $placeholders);

        $sql = "INSERT INTO `" . static::$table . "` ($columns) VALUES ($placeholders)";

        $result = $this->db->execSql($sql, $properties);

        unlink(substr($filePath, 11, strlen($filePath) - 13));

        return $result;
    }

    public function update()
    {
        $properties = get_object_vars($this);
        unset($properties['db']);
        unset($properties['table']);
        $id = $properties['id'] ?? null;

        if (!$id) {
            $_SESSION['error_message'] = "Egyedi azonosító nincs megadva!";
            return false;
        }

        $filePath = "";

        unset($properties['id']); // Exclude 'id' for the update values

        $setClauseParts = [];
        foreach (array_keys($properties) as $key) {
            if ($key == 'cover'){
                if (str_starts_with($properties[$key], 'LOAD_FILE')){
                    $setClauseParts[] = $key . " = " . $properties[$key];
                    $filePath = $properties[$key];
                }
                unset($properties[$key]);
            }
            else{
                $setClauseParts[] = "$key = :$key";
            }
        }
        $setClause = implode(', ', $setClauseParts);

        $sql = "UPDATE `" . static::$table . "` SET $setClause WHERE id = :id";

        // Add 'id' back for the WHERE clause
        $properties['id'] = $id;

        $result = $this->db->execSql($sql, $properties);

        if ($filePath != ""){
            unlink(substr($filePath, 11, strlen($filePath) - 13));
        }

        return $result;
    }
}
