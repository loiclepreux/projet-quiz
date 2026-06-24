<?php
require_once 'config.php';

$reponse_id = (int) ($_POST['reponse'] ?? 0);

$stmt = $pdo->prepare("SELECT est_bonne FROM reponses WHERE id = :id");
$stmt->execute(['id' => $reponse_id]);
$row = $stmt->fetch();

echo ($row && $row['est_bonne']) ? "1" : "0";