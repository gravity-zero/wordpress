<?php get_header(); ?>

	<div class="hero">
	</div>

<?php if($_SERVER['REQUEST_URI'] !== get_home_url(). "/ajouter-recette/"): ?>
	<div class=" me-4 d-flex justify-content-end">
		<a href="<?= get_home_url(). "/ajouter-recette/" ?>">
			<button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
       	Ajouter une recette
		 	</button>
		</a>
	</div>
<?php endif ?>

<?php if (have_posts()) : ?>
	<div class="cardContainer">
		<?php while (have_posts()) : ?>
			<?php the_post(); ?>

				<div class="card">

					<img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="...">
					<div class="card-body">

						<?php if (get_post_meta(get_the_ID(), 'futur_sponso', true)) : ?>
							<div class="alert alert-primary" role="alert">
								Contenu Sponsorisé
							</div>
						<?php endif; ?>

						<h5 class="card-title"><?php the_title(); ?></h5>
						<p><small><?php the_terms(get_the_ID(), 'style'); ?></small></p>
						<p class="card-text"><?php the_excerpt(); ?></p>
						<p style="font-size:small;"><?php the_author(); ?></p>

							<a class="linkBtn" href="<?php the_permalink(); ?>" >
								<button class="btn btnInfo">voir</button>
							</a>
				
					</div>
				</div>



		<?php endwhile; ?>
	</div>

<?php endif; ?>


<?php get_footer(); ?>
