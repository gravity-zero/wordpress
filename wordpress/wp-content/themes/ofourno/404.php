<?php
/**
 * Template Name: 404
 */
?>
<?php get_header(); ?>
<section class="page_404">
	<div class="container">
		<div class="row">
		<div class="col-sm-12 ">
			<div class="col-sm-10 col-sm-offset-1  text-center">
			<div class="four_zero_four_bg">
				<h1 class="text-center">404</h1>
			</div>
			<div class="contant_box_404">
				<h3 class="h2">Vous êtes perdu ?</h3>
				<p>La page recherché n'existe pas :/</p>
				<a href="<?= get_home_url() ?>" class="link_404">Retourner à l'accueil</a>
			</div>
			</div>
		</div>
		</div>
	</div>
</section>
<?php get_footer(); ?>