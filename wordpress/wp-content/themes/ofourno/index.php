<?php get_header(); ?>

<?php $loop = new WP_Query( array( 'post_type' => 'recette', 'posts_per_page' => '6' ) ); ?>
<div class="row row-cols-1 row-cols-md-3 g-4">
<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
    <div class="col">
        <div class="card">
            <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title"><?php the_title(); ?></h5>
                <p><small><?php the_terms(get_the_ID(), 'style'); ?></small></p>
                <p class="card-text"><?php the_excerpt(); ?></p>
                <p style="font-size:small;"><?php the_author(); ?></p>
                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Lire plus</a>
            </div>
        </div>
    </div>
<?php endwhile; wp_reset_query(); ?>
</div>
<?php get_footer(); ?>


