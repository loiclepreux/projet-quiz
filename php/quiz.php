<?php
session_start();
require_once __DIR__ . '/jeux.php';

$jeu    = $_GET['jeu'] ?? '';
$niveau = $_GET['niveau'] ?? '';

// Jeu ou niveau absent / inconnu : retour au choix des quiz
if (!isset($JEUX[$jeu]) || !in_array($niveau, $NIVEAUX, true)) {
    header('Location: play.php');
    exit;
}

include __DIR__ . '/../php-annexe/head.php';
?>

<body>
    <?php
    include __DIR__ . '/../php-annexe/header.php';
    include __DIR__ . '/../php-annexe/nav.php';
    ?>

    <main class="quiz-container"
        data-jeu="<?= htmlspecialchars($jeu) ?>"
        data-niveau="<?= htmlspecialchars($niveau) ?>"
        data-connecte="<?= isset($_SESSION['user_id']) ? '1' : '0' ?>">
        <h2 id="quiz-title"><?= htmlspecialchars($JEUX[$jeu]) ?> - Niveau <?= htmlspecialchars($niveau) ?></h2>
        <div class="quiz-infos">
            <span id="progression"></span>
            <span id="timer"></span>
        </div>
        <div id="question-block"><p>Chargement des questions...</p></div>
        <div id="score-display"></div>
    </main>

    <?php include __DIR__ . '/../php-annexe/footer.php'; ?>
    <?php include __DIR__ . '/../php-annexe/log.php'; ?>
    <script src="../js/dynamics.js"></script>
</body>
</html>
