<?php

namespace TaskFlow\Core;

use PDO;
use PDOException;

class Database
{
    private static $connection = null;

    public static function connect()
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
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
