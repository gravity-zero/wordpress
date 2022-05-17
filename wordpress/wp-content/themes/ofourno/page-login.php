<?php
/**
 * Template Name: Login
 * Template Post Type: page
 */
?>


<?php get_header(); ?>

	<form class="loginForm" action="<?= home_url( '/wp-login.php' ); ?>" method="post" name="form">
		<div class="mb-3">
			<input class="inputLogin" type="email" placeholder="Email" name="log">
		</div>
		<div class="mb-3">
			<input class="inputLogin" type="password" placeholder="mot de passe" name="pwd">
		</div>
        <div class="mb-3">
            <label for="rememberLogin"
            <input type="checkbox" name="rememberme" id="rememberLogin" checked>
        </div>
		<div class="mb-3">
		<a href="/login">			
			<button type="submit" class="btn btnLogin">Se connecter</button>	
		</a>
		</div>
			<div class="">Vous n'avez pas de compte ? 
				<a href="/register" class="button">S'inscrire</a>
			</div>
	</form>



<?php get_footer(); ?>