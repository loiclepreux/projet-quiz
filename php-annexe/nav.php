<?php
$base        = str_contains($_SERVER['PHP_SELF'], '/php/') ? '../' : '';
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="menu">
    <?php if ($currentPage !== 'index.php'): ?>
        <a href="<?= $base ?>index.php" class="btn-nav">Accueil</a>
    <?php endif; ?>

    <?php if ($currentPage !== 'play.php'): ?>
        <a href="<?= $base ?>php/play.php" class="btn-nav">Play</a>
    <?php endif; ?>

    <?php if ($currentPage !== 'score.php'): ?>
        <a href="<?= $base ?>php/score.php" class="btn-nav">Score</a>
    <?php endif; ?>

    <?php if ($currentPage !== 'forum.php'): ?>
        <a href="<?= $base ?>php/forum.php" class="btn-nav">Forum</a>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <span class="btn-nav user-greeting">👾 <?= htmlspecialchars($_SESSION['pseudo']) ?></span>
        <a href="<?= $base ?>php/logout.php" class="btn-nav">Déconnexion</a>
    <?php else: ?>
        <a href="#" class="btn-nav" id="loginBtn">Login</a>
    <?php endif; ?>
</nav>
