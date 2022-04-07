<?php
/**
 * Template Name: Login
 * Template Post Type: page
 */
?>


<?php get_header(); ?>

	<form class="loginForm" action="<?php echo site_url( '/wp-login.php' ); ?>" method="post" name="form">
		<div class="mb-3">
			<input class="inputLogin" type="email" placeholder="Email" name="log">
		</div>
		<div class="mb-3">
			<input class="inputLogin" type="password" placeholder="mot de pass" name="password">
		</div>
		<div class="mb-3">
			<button type="submit" class="btn btnLogin">Se connecter</button>	
		</div>
			<div class="">Vous n'avez pas de compte ? 
				<a href="/register" class="button">S'inscrire</a>
			</div>
	</form>



<?php get_footer(); ?>