<?php
require_once __DIR__ . '/securite.php'; // session protégée
session_destroy();
header('Location: ../index.php');
exit;
