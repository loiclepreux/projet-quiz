<?php
require_once __DIR__ . '/securite.php'; // session protégée
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

if ($action === 'register') {
    $pseudo = trim($_POST['pseudo'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $mdp    = $_POST['password'] ?? '';

    if (empty($pseudo) || empty($email) || empty($mdp)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
        exit;
    }

    // Vérifications : pseudo de 3 à 30 caractères, vrai email, mot de passe de 8 caractères minimum
    if (mb_strlen($pseudo) < 3 || mb_strlen($pseudo) > 30) {
        echo json_encode(['success' => false, 'message' => 'Le pseudo doit faire entre 3 et 30 caractères.']);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
        echo json_encode(['success' => false, 'message' => 'Adresse email invalide.']);
        exit;
    }
    if (strlen($mdp) < 8 || strlen($mdp) > 72) {
        echo json_encode(['success' => false, 'message' => 'Le mot de passe doit faire au moins 8 caractères.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE pseudo = :pseudo OR email = :email");
    $stmt->execute(['pseudo' => $pseudo, 'email' => $email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Pseudo ou email déjà utilisé.']);
        exit;
    }

    $hash = password_hash($mdp, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, email, mot_de_passe) VALUES (:pseudo, :email, :mdp)");
    $stmt->execute(['pseudo' => $pseudo, 'email' => $email, 'mdp' => $hash]);

    session_regenerate_id(true); // nouvel identifiant de session (anti vol de session)
    $_SESSION['user_id'] = (int) $pdo->lastInsertId();
    $_SESSION['pseudo']  = $pseudo;

    echo json_encode(['success' => true, 'pseudo' => $pseudo]);
    exit;
}

if ($action === 'login') {
    $identifiant = trim($_POST['identifiant'] ?? '');
    $mdp         = $_POST['password'] ?? '';

    if (empty($identifiant) || empty($mdp)) {
        echo json_encode(['success' => false, 'message' => 'Champs requis.']);
        exit;
    }

    // Anti force brute : 5 erreurs par adresse IP = 15 minutes d'attente
    $attente = limite_attente();
    if ($attente > 0) {
        echo json_encode(['success' => false, 'message' => 'Trop de tentatives. Réessaie dans ' . ceil($attente / 60) . ' minute(s).']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, pseudo, mot_de_passe FROM utilisateurs WHERE pseudo = :pseudo OR email = :email");
    $stmt->execute(['pseudo' => $identifiant, 'email' => $identifiant]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($mdp, $user['mot_de_passe'])) {
        limite_echec();
        echo json_encode(['success' => false, 'message' => 'Identifiant ou mot de passe incorrect.']);
        exit;
    }

    limite_reset();
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['pseudo']  = $user['pseudo'];

    echo json_encode(['success' => true, 'pseudo' => $user['pseudo']]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action inconnue.']);
