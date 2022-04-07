<?php
    // init var for header
    $user = wp_get_current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body>
    <section class="header d-flex justify-content-between align-items-center">

            <div class="icon">
                <a href="/"><img src="<?= home_url(); ?>/wp-content/uploads/2022/04/favicon.png" alt="ofourno logo'" /></a>
            </div>
            <h1 class="title">Ôfourno</h1></div>     


        <?php if (is_user_logged_in()) : ?>
            <?php global $current_user; get_currentuserinfo(); ?>
                <div class="">
                <!-- <span class="">Hello 👋</span> -->
                <a class="button" href="<?= wp_logout_url('/') ?>">Déconnexion</a>
                </div>
            <?php else : ?>
            <div class="me-4">
                <a class="button me-4" href="/register">S'inscrire</a>
                <a class="button" href="/login">Connexion</a>
            </div>
        <?php endif; ?>

    </section>
   