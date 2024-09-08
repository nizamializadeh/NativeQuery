<?php

class Database {
    protected static $connection;

    public static function connect() {
        if (!self::$connection) {
            $config = include(__DIR__ . '/../config/database.php');

            self::$connection = new PDO(
                "{$config['driver']}:host={$config['host']};dbname={$config['database']}",
                $config['username'],
                $config['password']
            );

            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$connection;
    }
}
