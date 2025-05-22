<?php require_once __DIR__ . '/service/utils.php'; ?>

<?php get_header(); ?>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : ?>
    <div class="card-container">
        <?php the_post(); ?>

        <?php 
            $images = get_children([
                'post_parent' => get_the_ID(),
                'post_type' => 'attachment',
                'post_mime_type' => 'image'
            ]);

            $ingredients = get_post_meta(get_the_ID(), '_ingredients', true);
            $difficulty = get_post_meta(get_the_ID(), '_difficulty', true);
            $duration = str_replace(":", "h", get_post_meta(get_the_ID(), '_duration', true));
            $cost = get_post_meta(get_the_ID(), '_cost', true);
            $steps = explode("\n", get_the_content());
            $count = 1;

            // SVG icons
            $svg_grinder = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/grinder-kitchen.svg');
            $svg_ingredients = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/ingredients.svg');
            $svg_difficulty = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/chef-hat.svg');
            $svg_price = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/price-tag-euro.svg');
            $svg_whip = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/whip.svg');
            $svg_time = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/time-fwd.svg');

            $meal_type = get_the_terms(get_the_ID(), 'meal_type');
            $meal_type_names = !empty($meal_type) ? array_map(function($term) { return $term->name; }, $meal_type) : [];

            $meal_type_conversion_name = [
											"breakfast" => "Petit-déjeuner",
										  	"lunch" => "Déjeuner",
										  	"snack" => "Goûter",
										  	"dinner" => "Dîner",
										  	"totry" => "À essayer"
										];
        ?>

        <div class="card mb-3">
            <img id="main-image" src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="Image principale de la recette">

            <div class="card-body">
                <h1 class="card-title"><?php the_title(); ?></h1>

                <?php if (!empty($meal_type_names)): ?>
                    <div class="recipe-taxonomies">
                        <?php foreach ($meal_type_names as $term_name): ?>
                            <span class="taxonomy-tag"><?php echo esc_html(get_value_from_key($meal_type_conversion_name, $term_name)); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="difficult-cost">
                    <span class="difficulty"><span class="recipe-icon-inline"><?= $svg_difficulty ?></span><b><?= esc_html($difficulty); ?></b></span>
                    <span class="time"><span class="recipe-icon-inline"><?= $svg_time ?></span><b><?= "&nbsp;".esc_html($duration); ?></b></span>
                    <span class="cost"><span class="recipe-icon-inline"><?= $svg_price ?></span><b><?= esc_html($cost); ?></b></span>
                </div>

                <hr />

                <?php if (!empty($ingredients)): ?>
                    <div class="ingredients container">
                        <div class="ingredients title">
                            <span class="recipe-icon-inline"><?= $svg_ingredients ?></span><h3> Ingrédients </h3>
                            <span class="line"></span>
                        </div>
                        <ul class="ingredients list">
                            <?php foreach ($ingredients as $ingredient): ?>
                                <li>
                                    <?= ucfirst(esc_html($ingredient['ingredient'])); ?> 
                                    <b><?= esc_html($ingredient['quantity']); ?></b>
                                    <b><?= esc_html($ingredient['unit']); ?></b>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="steps container">
                    <div class="steps title">
                        <span class="recipe-icon-inline whip"><?= $svg_whip ?></span><h3> Préparation </h3>
                        <span class="line"></span>
                    </div>
                    
                    <div class="steps content">
                        <?php foreach($steps as $step): ?>
                            <?php if(!empty(trim($step))) :?>
                            <h5 class="step">ÉTAPE <?= $count ?> </h5>
                            <?=  "<p>" . esc_html(trim($step)) ."</p>"; ?>
                            <?php $count++; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <p class="card-text" style="color: black">
                    Publié le <small class="text-muted"><i><?php the_date(); ?></i></small> par <b><?php the_author(); ?></b>
                </p>

                <?php if (current_user_can('edit_post', get_the_ID())): ?>  
                    <a href="<?php echo home_url('/edit-recipe?post_id=' . get_the_ID()); ?>" class="btn btnSubmit">Éditer cette recette</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="other-images-container d-flex justify-content-start">
            <?php if ( empty($images) ): ?>
                <p>Aucune image trouvée</p>
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

<script>
    function changeMainImage(newImageUrl) {
        document.getElementById('main-image').src = newImageUrl;
    }
</script>
