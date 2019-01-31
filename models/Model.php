<?php

require 'UserModel.php';
require 'CheckErrors.php';

class Model {

    private static $servername = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $dbname = "warera";
    private static $charset = "utf8mb4";

    protected static function connect() {

        try {
            $dsn = "mysql:host=".self::$servername.";dbname=".self::$dbname.";charset=".self::$charset;
            $pdo = new PDO($dsn, self::$username, self::$password);
            // S'il y a une erreur, on l'affiche à la place de la requete pdo
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            echo "Connection failed : ".$e->getMessage();
        }
    }

    public static function createDB() {

        self::connect()->query('CREATE TABLE IF NOT EXISTS users (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            role VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            level INT(11) NOT NULL,
            strength INT(11) NOT NULL,
            eco_skill INT(11) NOT NULL)'
        );

        self::connect()->query('CREATE TABLE IF NOT EXISTS users_inventory (
            id_user INT(11) AUTO_INCREMENT PRIMARY KEY,
            iron INT(11) NOT NULL,
            cereals INT(11) NOT NULL,
            weapons INT(11) NOT NULL,
            food INT(11) NOT NULL)'
        );

        self::connect()->query('CREATE TABLE IF NOT EXISTS users_gallery (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            id_user INT(11),
            status INT(11) NOT NULL)'
        );
    }
}