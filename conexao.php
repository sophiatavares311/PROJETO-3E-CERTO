<?php
$host = getenv('MYSQLHOST') ?: '127.0.0.1';
$db   = getenv('MYSQLDATABASE') ?: 'railway';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$port = getenv('MYSQLPORT') ?: '3306';

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ];
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass, $options);

    $pdo->exec("CREATE TABLE IF NOT EXISTS alunos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        serie VARCHAR(50) NOT NULL,
        data_nascimento DATE NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS mentorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        aluno_id INT NOT NULL,
        mentor VARCHAR(100) NOT NULL,
        data_mentoria DATE NOT NULL,
        modalidade ENUM('Presencial', 'Online') NOT NULL,
        link_local VARCHAR(255) NOT NULL,
        resumo TEXT NOT NULL,
        FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

} catch (PDOException $e) {
    die("Falha crítica no sistema de dados: " . $e->getMessage());
}
?>
