<?php
session_start();
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

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE pseudo = :pseudo OR email = :email");
    $stmt->execute(['pseudo' => $pseudo, 'email' => $email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Pseudo ou email déjà utilisé.']);
        exit;
    }

    $hash = password_hash($mdp, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, email, mot_de_passe) VALUES (:pseudo, :email, :mdp)");
    $stmt->execute(['pseudo' => $pseudo, 'email' => $email, 'mdp' => $hash]);

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

    $stmt = $pdo->prepare("SELECT id, pseudo, mot_de_passe FROM utilisateurs WHERE pseudo = :id OR email = :id");
    $stmt->execute(['id' => $identifiant]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($mdp, $user['mot_de_passe'])) {
        echo json_encode(['success' => false, 'message' => 'Identifiant ou mot de passe incorrect.']);
        exit;
    }

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['pseudo']  = $user['pseudo'];

    echo json_encode(['success' => true, 'pseudo' => $user['pseudo']]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Action inconnue.']);
