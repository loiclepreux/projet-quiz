<?php
// Enregistre le score de la partie terminée. Le score vient de la session, jamais du navigateur.
require_once __DIR__ . '/securite.php'; // session protégée
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');

$partie = $_SESSION['partie'] ?? null;

if (!$partie || count($partie['repondu']) < count($partie['bonnes'])) {
    echo json_encode(['success' => false, 'message' => 'Aucune partie terminée à enregistrer.']);
    exit;
}
if ($partie['sauvee']) {
    echo json_encode(['success' => false, 'message' => 'Ce score est déjà enregistré.']);
    exit;
}

// Connecté : on prend le pseudo du compte. Sinon : le pseudo tapé à la fin de la partie.
if (isset($_SESSION['user_id'])) {
    $pseudo = $_SESSION['pseudo'];
} else {
    $pseudo = trim($_POST['pseudo'] ?? '');
    if (mb_strlen($pseudo) < 2 || mb_strlen($pseudo) > 30) {
        echo json_encode(['success' => false, 'message' => 'Pseudo : entre 2 et 30 caractères.']);
        exit;
    }
    // On ne peut pas jouer sous le pseudo de quelqu'un qui a un compte
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE pseudo = :pseudo");
    $stmt->execute(['pseudo' => $pseudo]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Ce pseudo appartient à un compte : connecte-toi pour l\'utiliser.']);
        exit;
    }
}

$stmt = $pdo->prepare("SELECT id FROM jeux WHERE nom = :nom");
$stmt->execute(['nom' => $partie['jeu']]);
$jeuRow = $stmt->fetch();

if (!$jeuRow) {
    echo json_encode(['success' => false, 'message' => 'Jeu introuvable']);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO scores (pseudo, id_jeu, niveau, score, date_jeu)
    VALUES (:pseudo, :id_jeu, :niveau, :score, NOW())
");
$stmt->execute([
    'pseudo' => $pseudo,
    'id_jeu' => $jeuRow['id'],
    'niveau' => $partie['niveau'],
    'score'  => $partie['score'],
]);

$_SESSION['partie']['sauvee'] = true;
echo json_encode(['success' => true, 'pseudo' => $pseudo]);
