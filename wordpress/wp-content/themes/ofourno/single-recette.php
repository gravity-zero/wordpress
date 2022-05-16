<?php get_header(); ?>
<div class="single-recette">
<?php if (have_posts()) : ?>
	<?php while (have_posts()) : ?>
		<?php the_post(); ?>

		<div class="card mb-3">
			<img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="...">
			<div class="card-body">
				<h2 class="card-title"><?php the_title(); ?></h2>
				<p class="card-text" style="color: black"><?php the_content(); ?></p>
				<p class="card-text" style="color: black">Posté le <small class="text-muted"><?php the_date(); ?></small> Par <?php the_author(); ?></p>
			</div>
		</div>

	<?php endwhile; ?>
<?php endif; ?>
</div>
<?php get_footer(); ?>
