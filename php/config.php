<?php
// ====================================
// 🔧 Configuration base de données  =
// ====================================

// Chargement du fichier .env (cherche depuis la racine du projet)
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

$host     = $_ENV['DB_HOST'] ?? 'localhost';
$port     = $_ENV['DB_PORT'] ?? '3306';
$dbname1  = $_ENV['DB_NAME'] ?? '';
$username = $_ENV['DB_USER'] ?? '';
// Aucun mot de passe écrit ici : il est uniquement dans le fichier .env (jamais sur GitHub)
$password = $_ENV['DB_PASS'] ?? '';

$dsn = "mysql:host={$host};port={$port};dbname={$dbname1};charset=utf8";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    exit('Erreur de connexion à la base de données.');
}
?>
