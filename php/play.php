<?php
require_once __DIR__ . '/securite.php'; // session protégée
include 'config.php';
include 'jeux.php';

// Seulement les jeux qui ont vraiment des questions dans la base
$jeuxDispo = $pdo->query("
    SELECT DISTINCT j.nom
    FROM jeux j
    JOIN questions q ON q.id_jeu = j.id
    ORDER BY j.nom
")->fetchAll(PDO::FETCH_COLUMN);
?>

<?php include __DIR__ . '/../php-annexe/head.php'; ?>

<body>

    <?php include __DIR__ . '/../php-annexe/header.php'; ?>

    <?php include __DIR__ . '/../php-annexe/nav.php'; ?>

    <main class="selection-quiz">

        <h2>Sélectionne ton quiz jeune gamer !!!</h2>

        <div class="quiz-grid">
            <?php foreach ($jeuxDispo as $nom): ?>
                <a href="quiz.php?jeu=<?= urlencode($nom) ?>" class="quiz-card">
                    <?= htmlspecialchars($JEUX[$nom] ?? $nom) ?>
                </a>
            <?php endforeach; ?>

            <?php if (!$jeuxDispo): ?>
                <p>Aucun quiz disponible pour le moment.</p>
            <?php endif; ?>
        </div>

    </main>

    <?php include __DIR__ . '/../php-annexe/footer.php'; ?>

    <?php include __DIR__ . '/../php-annexe/difficulty.php'; ?>
    <?php include __DIR__ . '/../php-annexe/log.php'; ?>
</body>
<script src="../js/play.js"></script>

</html>