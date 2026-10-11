<?php
// Démarre une partie : renvoie 10 questions différentes, SANS les bonnes réponses.
// Les bonnes réponses restent sur le serveur, dans la session.
require_once __DIR__ . '/securite.php'; // session protégée
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/jeux.php';

header('Content-Type: application/json; charset=utf-8');

$jeu    = $_GET['jeu'] ?? '';
$niveau = $_GET['niveau'] ?? '';

if (!isset($JEUX[$jeu]) || !in_array($niveau, $NIVEAUX, true)) {
    echo json_encode(['success' => false, 'message' => 'Jeu ou niveau inconnu']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT q.id, q.question
    FROM questions q
    JOIN jeux j ON q.id_jeu = j.id
    WHERE j.nom = :jeu AND q.niveau = :niveau
    ORDER BY RAND()
    LIMIT 10
");
$stmt->execute(['jeu' => $jeu, 'niveau' => $niveau]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$questions) {
    echo json_encode(['success' => false, 'message' => 'Aucune question trouvée']);
    exit;
}

$stmtR = $pdo->prepare("SELECT id, reponse, est_bonne FROM reponses WHERE id_question = :id ORDER BY RAND()");
$bonnes = [];

foreach ($questions as &$q) {
    $stmtR->execute(['id' => $q['id']]);
    $q['reponses'] = [];
    foreach ($stmtR->fetchAll(PDO::FETCH_ASSOC) as $r) {
        if ((int) $r['est_bonne'] === 1) {
            $bonnes[(int) $q['id']] = (int) $r['id'];
        }
        $q['reponses'][] = ['id' => (int) $r['id'], 'reponse' => $r['reponse']];
    }
}
unset($q);

// La partie en cours est retenue côté serveur
$_SESSION['partie'] = [
    'jeu'      => $jeu,
    'niveau'   => $niveau,
    'bonnes'   => $bonnes,
    'repondu'  => [],
    'score'    => 0,
    'sauvee'   => false,
];

echo json_encode([
    'success'   => true,
    'titre'     => $JEUX[$jeu],
    'questions' => $questions,
]);
