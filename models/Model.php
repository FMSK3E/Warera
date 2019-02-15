<?php

require 'UserModel.php';
require 'MarketModel.php';
require 'XModel.php';
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
            echo 'Connection failed : '.$e->getMessage();
        }
    }

    public static function createDB() {

        self::connect()->query('CREATE TABLE IF NOT EXISTS users (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            role VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            username VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            level INT(11) NOT NULL DEFAULT 1,
            strength INT(11) NOT NULL DEFAULT 1,
            eco_skill INT(11) NOT NULL DEFAULT 1,
            nationality VARCHAR(255) NOT NULL)'
        );

        self::connect()->query('CREATE TABLE IF NOT EXISTS users_inventory (
            id_user INT(11) AUTO_INCREMENT PRIMARY KEY,
            iron INT(11) NOT NULL DEFAULT 1000,
            cereals INT(11) NOT NULL DEFAULT 1000,
            weapons INT(11) NOT NULL DEFAULT 100,
            food INT(11) NOT NULL DEFAULT 100)'
        );

        self::connect()->query('CREATE TABLE IF NOT EXISTS users_wallet (
            id_user INT(11) AUTO_INCREMENT PRIMARY KEY,
            alg INT(11) NOT NULL DEFAULT 0,
            zim INT(11) NOT NULL DEFAULT 0)'
        );

        self::connect()->query('CREATE TABLE IF NOT EXISTS users_gallery (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            id_user INT(11),
            status INT(11) NOT NULL DEFAULT 0)'
        );

        self::connect()->query('CREATE TABLE IF NOT EXISTS product_market (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            id_seller INT(11),
            product VARCHAR(255) NOT NULL,
            stock VARCHAR(255) NOT NULL,
            price INT(11) NOT NULL,
            country_market VARCHAR(255) NOT NULL,
            currency_used VARCHAR(255) NOT NULL)'
        );

        self::connect()->query('CREATE TABLE IF NOT EXISTS countries (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            country VARCHAR(255) NOT NULL,
            currency VARCHAR(255) NOT NULL)'
        );

        /*self::connect()->query('INSERT INTO countries (country, currency) VALUES
            ("Zimbabwe", "ZIM"),
            ("Algeria", "ALG")
            WHERE (
            SELECT * FROM countries
            WHERE id=1)'
        );*/
    }
}