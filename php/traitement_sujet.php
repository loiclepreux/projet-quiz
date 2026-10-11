<?php
require_once __DIR__ . '/securite.php'; // session protégée
require_once __DIR__ . '/config.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: forum.php');
    exit;
}

if (
    isset($_POST['titre'], $_POST['message']) &&
    !empty(trim($_POST['titre'])) &&
    !empty(trim($_POST['message']))
) {
    $titre = trim($_POST['titre']);
    $message = trim($_POST['message']);
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO sujets (titre, message, user_id, date_creation) VALUES (:titre, :message, :user_id, NOW())");
    $stmt->execute([
        'titre' => $titre,
        'message' => $message,
        'user_id' => $user_id
    ]);

    $sujet_id = $pdo->lastInsertId();
    header("Location: sujet.php?id=$sujet_id");
    exit;
} else {
    echo "<p>❌ Erreur : titre ou message manquant.</p>";
    echo '<p><a href="forum.php">Retour au forum</a></p>';
}
