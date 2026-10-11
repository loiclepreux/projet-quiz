<?php
// =========================================================
//  Sécurité commune à tout le site
//  (inclus en haut de chaque page à la place de session_start)
// =========================================================

// ---------- Session protégée ----------
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1'); // refuse un identifiant de session inventé
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,                    // le JavaScript ne peut pas lire le cookie
        'samesite' => 'Lax',                   // un autre site ne peut pas envoyer de formulaire à ta place
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', // cookie seulement en HTTPS
    ]);
    session_start();
}

// ---------- Anti force brute, par adresse IP ----------
// (un robot peut jeter son cookie de session, pas son adresse IP)
function limite_fichier(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'inconnue';
    return sys_get_temp_dir() . '/quiz_login_' . md5($ip);
}

// Renvoie le nombre de secondes à attendre (0 = c'est bon)
function limite_attente(int $max = 5, int $duree = 900): int
{
    $fichier = limite_fichier();
    if (!is_file($fichier)) {
        return 0;
    }
    $data = json_decode((string) file_get_contents($fichier), true) ?: [];
    $essais = array_filter($data, fn($t) => $t > time() - $duree);
    if (count($essais) < $max) {
        return 0;
    }
    return max(1, min($essais) + $duree - time());
}

function limite_echec(int $duree = 900): void
{
    $fichier = limite_fichier();
    $data = is_file($fichier) ? (json_decode((string) file_get_contents($fichier), true) ?: []) : [];
    $data = array_values(array_filter($data, fn($t) => $t > time() - $duree));
    $data[] = time();
    file_put_contents($fichier, json_encode($data), LOCK_EX);
}

function limite_reset(): void
{
    @unlink(limite_fichier());
}

