<?php
if (!isset($pathPrefix)) {
    $pathPrefix = str_contains($_SERVER['PHP_SELF'], '/php/') ? '../' : '';
}
?>

<footer class="footer">
    <div class="footer-content">
        <p>© <?= date('Y') ?> Ultimate Gaming Quiz · Projet de formation, non affilié aux éditeurs des jeux cités.</p>
        <p>Designed by <span class="designer">Lepreux Loïc</span></p>
        <a href="<?= $pathPrefix ?>php-annexe/mention.php" class="footer-link">Mentions légales</a>
        <a href="<?= $pathPrefix ?>php-annexe/politique.php" class="footer-link">Politique de confidentialité</a>
    </div>
</footer>