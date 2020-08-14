<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/config/database.php";

Config::write('db.dsn', $DB_DSN );
//. ";dbname=camagru_db"
Config::write('db.user', $DB_USER);
Config::write('db.password', $DB_PASSWORD);

class Config
{

    static $confArray;

    public static function read($name)
    {
        return self::$confArray[$name];
    }

    public static function write($name, $value)
    {
        self::$confArray[$name] = $value;
    }
}

class Core
{
    public $pdo;
    private static $instance;

    private function __construct()
    {
        try {
            $this->pdo = new PDO(Config::read('db.dsn'), Config::read('db.user'),
                Config::read('db.password'));
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }

    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Core;
        }
        return self::$instance;
    }
}