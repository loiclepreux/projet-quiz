<?php
require_once __DIR__ . '/php/securite.php'; // session protégée
include 'php/config.php';
?>

<?php include 'php-annexe/head.php'; ?>

<body>

<?php include 'php-annexe/header.php'; ?>

<?php include 'php-annexe/nav.php'; ?>

    <main>
        <section class="presentation">

        <h2>Bienvenue sur Ultimate Gaming Quiz</h2>
        <p>
            Es-tu prêt à relever le défi ultime ? 🎮<br><br>
            Ultimate Gaming Quiz est un terrain d'affrontement pour tous les passionnés de jeux vidéo, des vétérans du rétro aux accros 
            du multijoueur en ligne. Teste tes connaissances sur les plus grands classiques comme Mario, Zelda, Final Fantasy, Call of Duty, 
            GTA et bien d'autres !
        </p>
        <p>
            ➤ Choisis ton mode de jeu : <strong>Facile</strong>, <strong>Moyen</strong> ou <strong>Difficile</strong>.<br>
            ➤ Affronte le chrono, fais les meilleurs scores et grimpe dans le classement.<br>
            ➤ Défie tes amis, prouve ta maîtrise et deviens le boss final du quiz !
        </p>
        <p>
            🔐 Tu peux te connecter pour sauvegarder ton score, ou simplement jouer pour le fun.<br>
            🔥 Que le meilleur gamer gagne !
        </p>
        </section>
    </main>

<?php include 'php-annexe/footer.php'; ?>

<?php include 'php-annexe/log.php'; ?>

</body>
</html>