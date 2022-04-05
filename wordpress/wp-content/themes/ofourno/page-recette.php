<?php

get_header(); ?>


<?php if (have_posts()) : ?>
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>

        <div class="card mb-3">
            <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title"><?php the_title(); ?></h5>
                <p class="card-text">Prix : <?= get_post_meta(get_the_ID(), 'prix', true) ?></p>
                <a href="<?= get_post_meta(get_the_ID(), 'billet', true) ?>">Acheter</a>
                <p class="card-text"><small class="text-muted"><?php the_date(); ?></small></p>
            </div>
        </div>

    <?php endwhile; ?>
<?php endif; ?>

    <form action="<?= admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="new_recette_form">
        <?php wp_nonce_field('random_action', 'random_nonce'); ?>
        <?php wp_referer_field(); ?>
        <div class="mb-3">
            <label for="title" class="form-label">titre</label>
            <input type="text" class="form-control" id="title" name="title">
        </div>
        <div class="mb-3">
            <label for="images" class="form-label">Images</label>
            <input type="file" id="images" name="images[]" accept="image/png, image/jpeg" multiple>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Contenu</label>
            <input type="text" class="form-control" id="content" name="content">
        </div>
        <div class="mb-3">
            <label for="ingredient" class="form-label">Ingrédients</label>
            <input type="text" class="form-control" id="ingredient" name="ingredient">
        </div>
        <div>
            <input type="submit" value="Soumettre">
        </div>
    </form>

<?php get_footer(); ?>