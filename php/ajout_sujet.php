<?php
session_start();
require_once __DIR__ . '/config.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: forum.php');
    exit;
}

// Vérifie que les champs sont remplis
if (!isset($_POST['titre'], $_POST['message']) || empty(trim($_POST['titre'])) || empty(trim($_POST['message']))) {
    header('Location: forum.php?erreur=champs_vides');
    exit;
}

$titre = trim($_POST['titre']);
$message = trim($_POST['message']);
$user_id = $_SESSION['user_id'];

try {
    // Insertion du sujet
    $stmt = $pdo->prepare("INSERT INTO sujets (titre, message, user_id) VALUES (:titre, :message, :user_id)");
    $stmt->execute([
        'titre' => $titre,
        'message' => $message,
        'user_id' => $user_id
    ]);

    // Redirige vers la page du sujet directement après création
    $newSujetId = $pdo->lastInsertId();
    header("Location: sujet.php?id=$newSujetId");
    exit;

} catch (PDOException $e) {
    echo "Erreur lors de l'insertion du sujet : " . $e->getMessage();
    exit;
}