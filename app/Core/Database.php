<?php

namespace TaskFlow\Core;

use PDO;
use PDOException;

class Database
{
    private static $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {

            $config = require __DIR__ . '/../../config/database.php';

            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

            try {
                self::$connection = new PDO(
                    $dsn,
                    $config['user'],
                    $config['pass'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                error_log("DB Connection Error: " . $e->getMessage());

                die("Database connection failed. Please try again later.");
            }
        }

        return self::$connection;
    }
    public static function getInstance(): PDO
    {
        return self::connect();
    }
}
