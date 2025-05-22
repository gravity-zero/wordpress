<?php
/**
 * Template Name: register
 *
 */
?>
<?php get_header(); ?>

<div class="register-container">
    <form class="register-card" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" autocomplete="off">
        <h2>Inscription</h2>
        <input type="hidden" name="action" value="register_treatment">
        <?php wp_nonce_field('register_action', 'register_nonce'); ?>

        <div class="mb-3">
            <label for="user_login" class="form-label">Login</label>
            <input class="form-control" type="text" id="user_login" name="user_login" placeholder="Votre login" required>
        </div>
        <div class="mb-3">
            <label for="user_email" class="form-label">Adresse email</label>
            <input class="form-control" type="email" id="user_email" name="user_email" placeholder="Votre email" required>
        </div>
        <div class="mb-3">
            <label for="user_pass" class="form-label">Mot de passe</label>
            <input class="form-control" type="password" id="user_pass" name="user_pass" placeholder="Votre mot de passe" required>
        </div>
        <div class="mb-3">
            <label for="user_pass_check" class="form-label">Confirmation mot de passe</label>
            <input class="form-control" type="password" id="user_pass_check" name="user_pass_check" placeholder="Confirmez le mot de passe" required>
        </div>
        <button class="btn btn-register mt-3" type="submit">S'inscrire</button>
    </form>
</div>

<?php get_footer(); ?>