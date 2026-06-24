<?php
session_start();
require_once __DIR__ . '/config.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: forum.php');
    exit;
}

// Vérifie les données POST
if (
    isset($_POST['sujet_id'], $_POST['message']) &&
    !empty(trim($_POST['message']))
) {
    $sujet_id = (int) $_POST['sujet_id'];
    $message = trim($_POST['message']);
    $user_id = $_SESSION['user_id'];

    // Insertion dans la base
    $stmt = $pdo->prepare("
        INSERT INTO reactions (sujet_id, user_id, message, date_creation)
        VALUES (:sujet_id, :user_id, :message, NOW())
    ");
    $stmt->execute([
        'sujet_id' => $sujet_id,
        'user_id' => $user_id,
        'message' => $message
    ]);

    // Redirection vers le sujet
    header("Location: sujet.php?id=$sujet_id");
    exit;
} else {
    // Si données manquantes
    echo "<p>❌ Erreur : message vide ou sujet introuvable.</p>";
    echo '<p><a href="forum.php">Retour au forum</a></p>';
}