<?php
session_start();
include 'config.php';
include '../php-annexe/head.php';

// Récupération des sujets
$sujets = $pdo->query("
    SELECT s.*, u.pseudo, COUNT(r.id) AS nb_reponses
    FROM sujets s
    JOIN utilisateurs u ON s.user_id = u.id
    LEFT JOIN reactions r ON r.sujet_id = s.id
    GROUP BY s.id
    ORDER BY s.date_creation DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
<div class="wrapper"> <!-- ← AJOUTÉ ICI -->

<?php include '../php-annexe/header.php'; ?>
<?php include '../php-annexe/nav.php'; ?>

<main class="forum-container">
    <h2>💬 Forum de discussion</h2>

    <!-- Formulaire pour créer un sujet -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <section class="nouveau-sujet">
        <h3>Créer un nouveau sujet</h3>
        <form action="ajout_sujet.php" method="post">
            <input type="text" name="titre" placeholder="Titre du sujet" required>
            <textarea name="message" rows="4" placeholder="Votre message initial" required></textarea>
            <button type="submit">📩 Poster</button>
        </form>
    </section>
    <?php else: ?>
    <p>🔒 Connecte-toi pour créer un sujet.</p>
    <?php endif; ?>

    <!-- Liste des sujets -->
    <section class="liste-sujets">
        <?php if (count($sujets) === 0): ?>
            <p>Aucun sujet pour le moment.</p>
        <?php else: ?>
            <div class="table-scroll">
        <table class="forum-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Date</th>
                        <th>Réponses</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sujets as $sujet): ?>
                        <tr>
                            <td><a href="sujet.php?id=<?= $sujet['id'] ?>"><?= htmlspecialchars($sujet['titre']) ?></a></td>
                            <td><?= htmlspecialchars($sujet['pseudo']) ?></td>
                            <td><?= date("d/m/Y H:i", strtotime($sujet['date_creation'])) ?></td>
                            <td><?= $sujet['nb_reponses'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </section>
</main>

<?php include '../php-annexe/footer.php'; ?>

</div> <!-- ← FERMETURE DU WRAPPER -->
<?php include __DIR__ . '/../php-annexe/log.php'; ?>
</body>
</html>