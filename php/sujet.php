<?php
session_start();
require_once __DIR__ . '/config.php';

$id_sujet = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id_sujet <= 0) {
    header('Location: forum.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT s.*, u.pseudo, DATE_FORMAT(s.date_creation, '%d/%m/%Y à %H:%i') AS date_format
    FROM sujets s
    JOIN utilisateurs u ON s.user_id = u.id
    WHERE s.id = :id
");
$stmt->execute(['id' => $id_sujet]);
$sujet = $stmt->fetch();

if (!$sujet) {
    echo "<p>Sujet introuvable.</p>";
    exit;
}

$stmt = $pdo->prepare("
    SELECT r.*, u.pseudo, DATE_FORMAT(r.date_creation, '%d/%m/%Y à %H:%i') AS date_format
    FROM reactions r
    JOIN utilisateurs u ON r.user_id = u.id
    WHERE r.sujet_id = :id
    ORDER BY r.date_creation ASC
");
$stmt->execute(['id' => $id_sujet]);
$reponses = $stmt->fetchAll();
?>

<?php include __DIR__ . '/../php-annexe/head.php'; ?>

<body>

<?php include __DIR__ . '/../php-annexe/header.php'; ?>
<?php include __DIR__ . '/../php-annexe/nav.php'; ?>

<main class="forum-container forum-sujet">
    <h2><?= htmlspecialchars($sujet['titre']) ?></h2>
    <p><strong><?= htmlspecialchars($sujet['pseudo']) ?></strong> — le <?= $sujet['date_format'] ?></p>
    <div class="message"><?= nl2br(htmlspecialchars($sujet['message'])) ?></div>

    <hr>

    <h3>💬 Réponses</h3>

    <?php if (count($reponses) > 0): ?>
        <?php foreach ($reponses as $rep): ?>
            <div class="reponse">
                <p><strong><?= htmlspecialchars($rep['pseudo']) ?></strong> — le <?= $rep['date_format'] ?></p>
                <div><?= nl2br(htmlspecialchars($rep['message'])) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune réponse pour le moment.</p>
    <?php endif; ?>

    <hr>

    <?php if (isset($_SESSION['user_id'])): ?>
        <h3>📝 Répondre</h3>
        <form action="ajouter_reponse.php" method="post" class="nouveau-sujet">
            <textarea name="message" rows="4" required placeholder="Votre réponse..."></textarea>
            <input type="hidden" name="sujet_id" value="<?= $id_sujet ?>">
            <button type="submit">Envoyer</button>
        </form>
    <?php else: ?>
        <p>🔐 Connecte-toi pour répondre.</p>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../php-annexe/footer.php'; ?>
<?php include __DIR__ . '/../php-annexe/log.php'; ?>

</body>
</html>
