<?php


$db_host = getenv('DB_HOST') ?: 'localhost';

$pdo = new PDO(
    'mysql:host=' . $db_host . ';dbname=tunehausdb;charset=utf8mb4',
    'root',
    '',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

// var_dump($pdo);