<?php
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

$jeu    = trim($_POST['jeu'] ?? '');
$niveau = trim($_POST['niveau'] ?? '');
$score  = (int) ($_POST['score'] ?? 0);
$pseudo = trim($_POST['pseudo'] ?? '');

if (empty($jeu) || empty($niveau) || empty($pseudo)) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM jeux WHERE nom = :nom");
$stmt->execute(['nom' => $jeu]);
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
    'niveau' => $niveau,
    'score'  => $score,
]);

echo json_encode(['success' => true]);
