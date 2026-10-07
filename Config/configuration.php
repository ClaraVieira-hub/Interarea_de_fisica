<?php


$env = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$env->safeLoad();

define("DB_NAME", $_ENV['DB_NAME'] ?? 'agua_lab');
define("DB_HOST", $_ENV['DB_HOST'] ?? 'localhost');
define("DB_USER", $_ENV['DB_USER'] ?? 'root');
define("DB_PASSWORD", $_ENV['DB_PASSWORD'] ?? '');
define("DB_PORT", $_ENV['DB_PORT'] ?? '3306');
