<?php
/**
 * Template Name: Login
 * Template Post Type: page
 */
?>

<?php get_header(); ?>

<div class="login-container">
	<form class="loginForm" action="<?= home_url( '/wp-login.php' ); ?>" method="post" name="form">
		<div class="mb-3">
			<input class="inputLogin" type="text" placeholder="login" name="log">
		</div>
		<div class="mb-3">
			<input class="inputLogin" type="password" placeholder="mot de passe" name="pwd">
		</div>
        <div class="mb-3">
            <label for="rememberLogin">Rester connecté ?</label>
            <input type="checkbox" name="rememberme" id="rememberLogin" checked>
        </div>
		<div class="mb-3">
			<button type="submit" name="wp-submit" class="btn btnLogin">Se connecter</button>
		</div>
    </form>
    <div class="register-redirection">Vous n'avez pas de compte ?
        <a href="/register" class="button">S'inscrire</a>
    </div>
</div>



<?php get_footer(); ?>