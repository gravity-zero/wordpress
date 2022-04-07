<?php
/**
 * Template Name: Register
 * Template Post Type: page
 */
?>


<?php get_header(); ?>

<form class="loginForm"action="<?php echo home_url().'/register'; ?>" method="post" name="form">

		<div class="mb-3">
			<input class="inputLogin" type="email" placeholder="Adresse email" name="user_email">
		</div>
		<div class="mb-3">
			<input class="inputLogin" type="password" placeholder="Votre mot de passe" name="user_pass">
		</div>
		<div class="mb-3">
			<input class="inputLogin" type="password" placeholder="Confirmez le mot de passe" name="user_pass">
		</div>
	<div>
		<a class="button">Mot de passe oublié ?</a>
	</div>
	<a href="/register">
		<button class="btn btnLogin" type="submit" class="">S'inscrire</button>
	</a>
</form>

<?php get_footer(); ?>