<?php

declare(strict_types=1);

namespace Model;

use PDO;
use PDOException;

require_once __DIR__ . '/../Config/configuration.php';

class Connection
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';

            try {
                self::$instance = new PDO($dsn, DB_USER, DB_PASSWORD, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
            } catch (PDOException $error) {
                throw new PDOException('Não foi possível conectar ao banco de dados: ' . $error->getMessage(), (int) $error->getCode(), $error);
            }
        }

        return self::$instance;
    }
}
