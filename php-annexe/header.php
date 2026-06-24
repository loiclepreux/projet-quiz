<?php
$pathPrefix = str_contains($_SERVER['PHP_SELF'], '/php/') ? '../' : '';
?>

<header class="banner">
    <img src="<?= $pathPrefix ?>img/logoquiz-remove.png" alt="Logo" class="logo">
    <div class="title-group">
        <h1>The Ultimate Gaming Quiz</h1>
        <p class="slogan">Teste tes connaissances gamer</p>
    </div>
</header>