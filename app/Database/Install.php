<?php

namespace App\Database;

use App\Models\RoomModel;
use App\Models\GuestModel;
use App\Views\Display;
use App\Database\Database;
use Exception;

class Install extends Database
{

    public function __construct($config){
        parent::__construct($config);
        if (!$this::dbExists()){
            $this->createDatabase();
            $this->createTables();
            $this->fillTables();
        }
        $this->setGlobalMaxAllowedPacket();
    }

    private function dbExists(): bool
    {
        try {
            $mysqli = $this->getConn('mysql');
            if (!$mysqli) {
                return false;
            }

            $query = sprintf("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '%s';", self::DEFAULT_CONFIG['database']);
            $result = $mysqli->query($query);

            if (!$result) {
                throw new Exception('Lekérdezési hiba: ' . $mysqli->error);
            }
            $exists = $result->num_rows > 0;

            return $exists;

        }
        catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            return false;
        }
        finally {
            // Ensure the database connection is always closed
            $mysqli?->close();
        }

    }

    private function getConn($dbName)
    {
        try {
            // Kapcsolódás az adatbázishoz
            $mysqli = mysqli_connect(self::DEFAULT_CONFIG["host"], self::DEFAULT_CONFIG["user"], self::DEFAULT_CONFIG["password"], $dbName);
    
            // Ellenőrizzük a csatlakozás sikerességét
            if (!$mysqli) {
                throw new Exception("Kapcsolódási hiba az adatbázishoz: " . mysqli_connect_error());
            }
    
            return $mysqli;
        } catch (Exception $e) {
            // Hibaüzenet megjelenítése a felhasználónak
            echo $e->getMessage();
            // Hibás csatlakozás esetén `null`-t ad vissza
            return null;
        }
    }

    private function setGlobalMaxAllowedPacket(){
        return $this->execSql("SET GLOBAL max_allowed_packet=1073741824;");
    }

    private function createDatabase(){
        return $this->execSql("CREATE DATABASE library CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }

    private function createTable(string $tableName, string $tableBody, string $dbName): bool
    {
        try {
            $sql = "
                CREATE TABLE `$dbName`.`$tableName`
                ($tableBody)
                ENGINE = InnoDB
                DEFAULT CHARACTER SET = utf8
                COLLATE = utf8_hungarian_ci;
            ";
            return (bool) $this->execSql($sql);

        } catch (Exception $e) {
            Display::message($e->getMessage(), 'error');
            return false;
        }
    }


    private function createTables($dbName = self::DEFAULT_CONFIG['database']){
        $this->createTablePublishers($dbName);
        $this->createTableCategories($dbName);
        $this->createTableWriters($dbName);
        $this->createTableBooks($dbName);
        $this->createTableRatings($dbName);
    }

    private function createTablePublishers($dbName){
        $tableBody = "
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
            publisher VARCHAR(50) NOT NULL";

        return $this->createTable('publishers', $tableBody, $dbName);
    }
    
    private function createTableCategories($dbName){
        $tableBody = "
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
            category VARCHAR(50) NOT NULL";

        return $this->createTable('categories', $tableBody, $dbName);
    }

    private function createTableWriters($dbName){
        $tableBody = "
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            writer VARCHAR(50),
            biography VARCHAR(400) NOT NULL";

        return $this->createTable('writers', $tableBody, $dbName);
    }

    private function createTableBooks($dbName){
        $tableBody = "
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
            writer_id INT, 
            publisher_id INT,
            category_id INT,
            title VARCHAR(50) NOT NULL, 
            cover mediumblob NULL, 
            ISBN VARCHAR(13) NOT NULL, 
            price INT NOT NULL,
            content VARCHAR(800) NOT NULL,
            FOREIGN KEY (`writer_id`) REFERENCES writers(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`publisher_id`) REFERENCES publishers(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`category_id`) REFERENCES categories(`id`) ON DELETE CASCADE";

        return $this->createTable('books', $tableBody, $dbName);
    }    

    private function createTableRatings($dbName){
        $tableBody = "
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            book_id INT NOT NULL,
            rating INT NOT NULL,
            FOREIGN KEY (`book_id`) REFERENCES books(`id`) ON DELETE CASCADE";

        return $this->createTable('ratings', $tableBody, $dbName);
    }

    private function getPropertiesAndFiles($tableName){
        $files = array_diff(scandir("../files/$tableName/"), array('.', '..'));
        $propertiesFile = fopen("../files/$tableName/properties.txt", "r");
        $properties = explode("\n", fread($propertiesFile, filesize("../files/$tableName/properties.txt")));
        fclose($propertiesFile);
        sort($files);
        return [$properties, $files];
    }

    private function fillTables($dbName = self::DEFAULT_CONFIG['database']){
        $this->fillTablePublishers($dbName);
        $this->fillTableCategories($dbName);
        $this->fillTableWriters($dbName);
        $this->fillTableBooks($dbName);
    }

    private function fillTablePublishers($dbName){
        $properties = $this->getPropertiesAndFiles("publishers")[0];

        for ($i = 0; $i < count($properties); $i++){
            $currProperties = explode(";", $properties[$i]);
            $sql = "INSERT INTO `$dbName`.`publishers`(publisher) VALUES('" . $currProperties[0] . "')";
            $this->execSql($sql);
        }
    }

    private function fillTableCategories($dbName){
        $properties = $this->getPropertiesAndFiles("categories")[0];

        for ($i = 0; $i < count($properties); $i++){
            $currProperties = explode(";", $properties[$i]);
            $sql = "INSERT INTO `$dbName`.`categories`(category) VALUES('" . $currProperties[0] . "')";
            $this->execSql($sql);
        }
    }

    private function fillTableWriters($dbName){
        $properties = $this->getPropertiesAndFiles("writers")[0];

        for ($i = 0; $i < count($properties); $i++){
            $currProperties = explode(";", $properties[$i]);
            $sql = "INSERT INTO `$dbName`.`writers`(writer, biography) VALUES('" . $currProperties[0] . "', '" . $currProperties[1] . "')";
            $this->execSql($sql);
        }
    }

    private function fillTableBooks($dbName){
        $properties = $this->getPropertiesAndFiles("books")[0];
        $files = $this->getPropertiesAndFiles("books")[1];

        for ($i = 0; $i < count($properties); $i++){
            $currProperties = explode(";", $properties[$i]);
            $sql = "INSERT INTO `$dbName`.`books`
            (writer_id, publisher_id, category_id, title, cover, ISBN, price, content) 
            VALUES(" . $currProperties[0] . ", " . $currProperties[1] . ", " . $currProperties[2] . ", '" . $currProperties[3] . "', LOAD_FILE('" . str_replace("\\","/",realpath("../files/books/" . $files[$i])) . "'), '" . $currProperties[4] . "', " . $currProperties[5] . ", '" . $currProperties[6] . "')";
            $this->execSql($sql);
        }
    }
}