<?php
// Détection automatique du chemin relatif
$pathPrefix = str_contains($_SERVER['PHP_SELF'], '/php/') ? '../' : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $pathPrefix ?>css/quiz.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <title>The Ultimate Gaming Quiz</title>
</head>