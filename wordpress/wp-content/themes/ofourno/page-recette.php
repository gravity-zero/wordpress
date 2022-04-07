<?php

get_header(); ?>

    <form class="formReceip" action="<?= admin_url('admin-post.php'); ?>" method="post" enctype="multipart/form-data">
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
        <input class="btn btnSubmit" type="submit" value="Soumettre">
        </div>
    </form>
<?php get_footer(); ?>