<?php
session_start(); 
include __DIR__ . '/../php-annexe/head.php'; 
?>

<body>
    <?php
    include __DIR__ . '/../php-annexe/header.php';
    include __DIR__ . '/../php-annexe/nav.php';
    ?>

    <main class="quiz-container" data-jeu="<?= htmlspecialchars($_GET['jeu']) ?>" data-niveau="<?= htmlspecialchars($_GET['niveau']) ?>" data-pseudo="<?= htmlspecialchars($_SESSION['pseudo'] ?? '') ?>">
        <h2 id="quiz-title"></h2>
        <div id="timer" style="font-size: 20px; margin-bottom: 10px; text-align: center;"></div>
        <div id="question-block"></div>
        <div id="score-display"></div>
    </main>

    <?php include __DIR__ . '/../php-annexe/footer.php'; ?>
    <?php include __DIR__ . '/../php-annexe/log.php'; ?>
    <script src="../js/dynamics.js"></script>
</body>
</html>