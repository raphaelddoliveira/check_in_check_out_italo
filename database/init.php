<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/app.php';

$dbPath = dirname(__DIR__) . '/' . config('db.path', 'database/app.sqlite');
$dbDir = dirname($dbPath);

if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $schema = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($schema);

    echo "Banco de dados criado com sucesso em: {$dbPath}\n";
} catch (PDOException $e) {
    echo "Erro ao criar banco de dados: " . $e->getMessage() . "\n";
    exit(1);
}
