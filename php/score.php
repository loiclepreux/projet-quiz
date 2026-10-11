<?php
require_once __DIR__ . '/securite.php'; // session protégée
include 'config.php';
include 'jeux.php';

try {
    $stmt = $pdo->query("
        SELECT s.pseudo, j.nom AS nom_jeu, s.niveau, s.score, s.date_jeu
        FROM scores s
        JOIN jeux j ON s.id_jeu = j.id
        ORDER BY s.score DESC, s.date_jeu DESC
        LIMIT 50
    ");
    $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des scores : " . $e->getMessage());
}
?>

<?php include __DIR__ . '/../php-annexe/head.php'; ?>

<body>

<?php include __DIR__ . '/../php-annexe/header.php'; ?>

<?php include __DIR__ . '/../php-annexe/nav.php'; ?>
        
<main class="quiz-container">
        <h2>🏆 Classement général</h2>
        <div class="table-scroll">
        <table class="score-table">
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Jeu</th>
                    <th>Niveau</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($scores as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['pseudo']) ?></td>
                        <td><?= htmlspecialchars($JEUX[$s['nom_jeu']] ?? $s['nom_jeu']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($s['niveau'])) ?></td>
                        <td><?= (int) $s['score'] ?>/10</td>
                        <td><?= date("d/m/Y H:i", strtotime($s['date_jeu'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </main>

<?php include __DIR__ . '/../php-annexe/footer.php'; ?>
<?php include __DIR__ . '/../php-annexe/log.php'; ?>

</body>
</html>