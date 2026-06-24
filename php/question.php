<?php
require_once 'config.php';

header('Content-Type: application/json');

// Sécurisation des paramètres
$jeu = $_GET['jeu'] ?? '';
$niveau = $_GET['niveau'] ?? '';

if (empty($jeu) || empty($niveau)) {
    echo json_encode(['success' => false, 'message' => 'Paramètres manquants']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT q.id, q.question, j.nom AS jeu
    FROM questions q
    JOIN jeux j ON q.id_jeu = j.id
    WHERE j.nom = :jeu AND q.niveau = :niveau
    ORDER BY RAND()
    LIMIT 1
");
$stmt->execute(['jeu' => $jeu, 'niveau' => $niveau]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    echo json_encode(['success' => false, 'message' => 'Aucune question trouvée']);
    exit;
}

$stmt = $pdo->prepare("SELECT id, reponse FROM reponses WHERE id_question = :id ORDER BY RAND()");
$stmt->execute(['id' => $question['id']]);
$reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'success' => true,
    'question' => $question,
    'reponses' => $reponses
]);
exit;