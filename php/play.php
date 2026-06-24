<?php
session_start();
include 'config.php';
?>

<?php include __DIR__ . '/../php-annexe/head.php'; ?>

<body>

<?php include __DIR__ . '/../php-annexe/header.php'; ?>

<?php include __DIR__ . '/../php-annexe/nav.php'; ?>

<main class="selection-quiz">

    <h2>Sélectionne ton quiz jeune gamer !!!</h2>

    <div class="quiz-grid">
        <a href="quiz.php?jeu=mario" class="quiz-card">Mario</a>
        <a href="quiz.php?jeu=zelda" class="quiz-card">Zelda</a>
        <a href="quiz.php?jeu=callofduty" class="quiz-card">Call of Duty</a>
        <a href="quiz.php?jeu=gta" class="quiz-card">GTA</a>
        <a href="quiz.php?jeu=fortnite" class="quiz-card">Fortnite</a>
        <a href="quiz.php?jeu=godofwar" class="quiz-card">God of War</a>
        <a href="quiz.php?jeu=tekken" class="quiz-card">Tekken</a>
        <a href="quiz.php?jeu=needforspeed" class="quiz-card">Need for speed</a>
        <a href="quiz.php?jeu=mortalkombat" class="quiz-card">Mortal kombat</a>
        <a href="quiz.php?jeu=ffrebirth" class="quiz-card">Final fantasy rebirth</a>
        <a href="quiz.php?jeu=residentevil" class="quiz-card">Resident evil</a>
        <a href="quiz.php?jeu=dukenukem" class="quiz-card">Duke nukem forever</a>
    </div>

</main>

<?php include __DIR__ . '/../php-annexe/footer.php'; ?>

<?php include __DIR__ . '/../php-annexe/difficulty.php'; ?>
    <?php include __DIR__ . '/../php-annexe/log.php'; ?>
</body>
<script src="../js/play.js"></script>
</html>