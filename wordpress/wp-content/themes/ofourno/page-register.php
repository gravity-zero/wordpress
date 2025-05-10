<?php
/**
 * Template Name: register
 *
 */
?>
<?php get_header(); ?>

<form class="loginForm" action="<?php echo home_url().'/register-treatment'; ?>" method="post">
        <div class="mb-3">
            <input class="inputLogin" type="text" placeholder="Login" name="user_login">
        </div>
		<div class="mb-3">
			<input class="inputLogin" type="email" placeholder="Adresse email" name="user_email">
		</div>
		<div class="mb-3">
			<input class="inputLogin" type="password" placeholder="Votre mot de passe" name="user_pass">
		</div>
		<div class="mb-3">
			<input class="inputLogin" type="password" placeholder="Confirmez le mot de passe" name="user_pass_check">
		</div>

		<button class="btn btnLogin" type="submit" class="">S'inscrire</button>
</form>

<?php get_footer(); ?>