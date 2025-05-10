<?php get_header(); ?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : ?>
    <div class="single-recette">
        <?php the_post(); ?>

        <?php 
            // Récupère les images attachées
            $images = get_children([
                'post_parent' => get_the_ID(),
                'post_type' => 'attachment',
                'post_mime_type' => 'image'
            ]);

            // Récupération des ingrédients
            $ingredients = get_post_meta(get_the_ID(), '_ingredients', true);
            $difficulty = get_post_meta(get_the_ID(), '_difficulty', true);
            $cost = get_post_meta(get_the_ID(), '_cost', true);
        ?>

        <div class="card mb-3">
            <!-- Image principale de la recette -->
            <img id="main-image" src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="Image principale de la recette">

            <div class="card-body">
                <h2 class="card-title"><?php the_title(); ?></h2>
                <div class="difficult-cost">
                    <span class="difficulty">Difficulté: <strong><?= esc_html($difficulty); ?></strong></span>
                    <span class="cost">Coût: <strong><?= esc_html($cost); ?></strong></span>
                </div>

                <?php if (!empty($ingredients)): ?>
                    <div class="ingredients-list mb-4">
                        <h4>Ingrédients :</h4>
                        <ul class="list-unstyled">
                            <?php foreach ($ingredients as $ingredient): ?>
                                <li>
                                    <strong><?= esc_html($ingredient['ingredient']); ?> :</strong> 
                                    <?= esc_html($ingredient['quantity']); ?> 
                                    <?= esc_html($ingredient['unit']); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="steps mb-4">
                    <h3>Étapes à suivre :</h3>
                    <ol>
                        <?php 
                            $steps = get_the_content();
                            echo nl2br(esc_html($steps));
                        ?>
                    </ol>
                </div>

                <p class="card-text" style="color: black">
                    Publié le <small class="text-muted"><?php the_date(); ?></small> 
                    Par <?php the_author(); ?>
                </p>
            </div>
        </div>

        <!-- Images supplémentaires sur la droite -->
        <div class="other-images-container d-flex justify-content-start">
            <?php if ( empty($images) ): ?>
                <script>console.log('Pas d\'images trouvées');</script>
            <?php else: ?>
                <?php foreach ( $images as $attachment_id => $attachment ): ?>
                    <img src="<?= wp_get_attachment_image_url( $attachment_id, 'thumbnail' ); ?>" 
                         class="mini-images img-thumbnail me-3" 
                         onclick="changeMainImage('<?= wp_get_attachment_image_url( $attachment_id, 'full' ); ?>')">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <?php comments_template('/comment-recipes.php', true); ?>
    <?php endwhile; ?>

<?php endif; ?>

<?php get_footer(); ?>

<!-- JS pour changer l'image principale -->
<script>
    function changeMainImage(newImageUrl) {
        // Change l'URL de l'image principale
        document.getElementById('main-image').src = newImageUrl;
    }
</script>
