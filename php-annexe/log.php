<?php
if (!isset($pathPrefix)) {
    $pathPrefix = str_contains($_SERVER['PHP_SELF'], '/php/') ? '../' : '';
}
?>
<div class="auth-overlay" id="auth-overlay" data-base="<?= $pathPrefix ?>">
    <div class="auth-container">
        <span class="close-auth" id="close-auth">✖</span>
        <div class="auth-box">

            <div class="auth-form login">
                <h2>Connexion</h2>
                <p class="auth-error" id="login-error"></p>
                <form id="login-form">
                    <input type="text" id="login-identifiant" placeholder="Pseudo ou email" required>
                    <input type="password" id="login-password" placeholder="Mot de passe" required>
                    <button type="submit">Se connecter</button>
                </form>
            </div>

            <div class="auth-form register">
                <h2>Inscription</h2>
                <p class="auth-error" id="register-error"></p>
                <form id="register-form">
                    <input type="text" id="register-pseudo" placeholder="Pseudo" required>
                    <input type="email" id="register-email" placeholder="Email" required>
                    <input type="password" id="register-password" placeholder="Mot de passe" required>
                    <button type="submit">S'inscrire</button>
                </form>
            </div>

        </div>
    </div>
</div>
<script src="<?= $pathPrefix ?>js/auth.js"></script>
