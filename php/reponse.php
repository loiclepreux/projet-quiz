<?php
// Vérifie UNE réponse de la partie en cours. C'est le serveur qui compte les points.
require_once __DIR__ . '/securite.php'; // session protégée
header('Content-Type: application/json; charset=utf-8');

$partie     = $_SESSION['partie'] ?? null;
$questionId = (int) ($_POST['question'] ?? 0);
$reponseId  = isset($_POST['reponse']) && $_POST['reponse'] !== '' ? (int) $_POST['reponse'] : null; // vide = temps écoulé

if (!$partie || !isset($partie['bonnes'][$questionId])) {
    echo json_encode(['success' => false, 'message' => 'Question inconnue']);
    exit;
}
if (in_array($questionId, $partie['repondu'], true)) {
    echo json_encode(['success' => false, 'message' => 'Question déjà répondue']);
    exit;
}

$bonne = $partie['bonnes'][$questionId];
$juste = $reponseId === $bonne;

$_SESSION['partie']['repondu'][] = $questionId;
if ($juste) {
    $_SESSION['partie']['score']++;
}

echo json_encode([
    'success' => true,
    'juste'   => $juste,
    'bonne'   => $bonne,
    'score'   => $_SESSION['partie']['score'],
]);
