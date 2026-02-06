<?php

namespace App\ControleFinanceiro\Db;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;

    public static function getConnection()
    {
        if (!self::$instance) {
            $c = require __DIR__ . '/../Connection/Connection.php';
            $dsn = "mysql:host={$c['host']};port={$c['port']};dbname={$c['db']};charset={$c['charset']}";

            try {
                self::$instance = new PDO($dsn, $c['user'], $c['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
            } catch (PDOException $e) {
                die("Erro de conexão: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}