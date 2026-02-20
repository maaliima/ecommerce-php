<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    public static function connect(array $config): PDO
    {
        $host = $config['host'] ?? 'localhost';
        $db = $config['db'] ?? '';
        $user = $config['user'] ?? 'root';
        $pass = $config['pass'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';

        try {
            $pdo = new PDO("mysql:host={$host};dbname={$db};charset={$charset}", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            http_response_code(500);
            die('Erro ao conectar ao banco de dados: ' . $e->getMessage());
        }
    }
}
