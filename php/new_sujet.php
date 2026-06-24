<?php
session_start();
require_once __DIR__ . '/config.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: forum.php');
    exit;
}
?>

<?php include '../php-annexe/head.php'; ?>
<?php include '../php-annexe/header.php'; ?>
<?php include '../php-annexe/nav.php'; ?>

<main class="forum-container">
    <h2>📝 Nouveau sujet</h2>
    <form action="traitement_sujet.php" method="post" class="form-sujet">
        <label for="titre">Titre du sujet :</label>
        <input type="text" name="titre" id="titre" required>

        <label for="message">Message :</label>
        <textarea name="message" id="message" rows="6" required></textarea>

        <button type="submit">📨 Poster le sujet</button>
    </form>
</main>

<?php include '../php-annexe/footer.php'; ?>