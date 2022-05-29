<?php get_header(); ?>

<?php if (have_posts()) : ?>
	<?php while (have_posts()) : ?>
<div class="single-recette">
		<?php the_post(); ?>

        <?php $images = get_children( array (
            'post_parent' =>  get_the_ID(),
            'post_type' => 'attachment',
            'post_mime_type' => 'image'
        )); ?>

		<div class="card mb-3">
			<img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="...">

			<div class="card-body">
				<h2 class="card-title"><?php the_title(); ?></h2>
				<p class="card-text" style="color: black"><?php the_content(); ?></p>
				<p class="card-text" style="color: black">Publié le <small class="text-muted"><?php the_date(); ?></small> Par <?php the_author(); ?></p>
			</div>
		</div>
        <div class="other-images-container">
            <?php if ( empty($images) ):
                echo "<script>console.log('pas d\'images trouvées)</script>"; ?>
            <?php else: ?>
                <?php foreach ( $images as $attachment_id => $attachment ): ?>
                    <img src="<?= wp_get_attachment_image_url( $attachment_id, 'thumbnail' ); ?>" class="mini-images" >
                <?php endforeach; ?>
            <?php endif ?>
        </div>
</div>
        <?php comments_template('/comment-recette.php', true); ?>
	<?php endwhile; ?>

<?php endif; ?>


<?php get_footer(); ?>
