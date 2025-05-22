<?php
/**
 * Template Name: login
 *
 */
?>
<?php  get_header(); ?>
<div class="login-container">
    <form class="login-card" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" autocomplete="off">
        <h2>Connexion</h2>
        <input type="hidden" name="action" value="login_treatment">
        <?php wp_nonce_field('login_action', 'login_nonce'); ?>

        <div class="mb-3">
            <label for="user_login" class="form-label">Login</label>
            <input class="form-control" type="text" id="user_login" name="user_login" placeholder="Votre login" required>
        </div>
        <div class="mb-3">
            <label for="user_password" class="form-label">Mot de passe</label>
            <input class="form-control" type="password" id="user_password" name="user_password" placeholder="Votre mot de passe" required>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="rememberme" id="rememberLogin" class="form-check-input" checked>
            <label for="rememberLogin" class="form-check-label">Rester connecté ?</label>
        </div>
        <input type="hidden" name="redirect_to" value="<?= home_url() ?>">
        <button type="submit" name="wp-submit" class="btn btn-login mt-3">Se connecter</button>
    </form>
    <div class="register-redirection">
        Vous n'avez pas de compte ?
        <a href="/register">S'inscrire</a>
    </div>
</div>

<?php get_footer(); ?>