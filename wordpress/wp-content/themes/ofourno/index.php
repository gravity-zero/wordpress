
<?php get_header(); ?>

<?php
$current_hour = date('H');
$meal_type = get_meal_type_by_time($current_hour);

$random_recipe_query = new WP_Query([
	'post_type' => 'recipes',
	'posts_per_page' => 1,
	'orderby' => 'rand',
	'tax_query' => [
		[
			'taxonomy' => 'meal_type',
			'field' => 'slug',
			'terms' => $meal_type,
		],
	],
]);
?>

	<div class="hero">
        <div class="hero-text">
            <?php
			if ($random_recipe_query->have_posts()) :
            $random_recipe_query->the_post(); ?>
            <p class="hero-p">Proposition de recette</p>
            <h1><?php the_title(); ?></h1>
			<p class="title_content">Laissez vous tenter par cette recette</p>
            <button class="btn hero-btn" type="button" onclick="window.location.href='<?php the_permalink(); ?>'">
                Découvrir
            </button>
        <?php endif; wp_reset_postdata(); ?>
		</div>
	</div>

<?php if(is_user_logged_in()): ?>
	<div class=" me-4 d-flex justify-content-end">
		<a href="<?= get_home_url(). "/add-recipe" ?>">
			<button class="btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
       	Ajouter une recette
		 	</button>
		</a>
	</div>
<?php endif ?>

<?php 

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$loop = new WP_Query(array(
    'post_type' => 'recipes',
    'posts_per_page' => 6,
    'paged' => $paged,
));

if ($loop->have_posts()) : ?>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php while ($loop->have_posts()) : $loop->the_post(); ?>
            <?php 
            $difficulty = get_post_meta(get_the_ID(), '_difficulty', true);
            $cost = get_post_meta(get_the_ID(), '_cost', true);
            $duration = str_replace(":", "h", get_post_meta(get_the_ID(), '_duration', true));

            $svg_difficulty = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/chef-hat.svg');
            $svg_price = file_get_contents(get_stylesheet_directory() . '/assets/images/icons/price-tag-euro.svg');
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
            <div class="col d-flex justify-content-center">
                <div class="card">
                    <img src="<?php the_post_thumbnail_url(); ?>" class="card-img-top" alt="Image principale de la recette">
                    <div class="card-body">
                        <h5 class="card-title"><?php the_title(); ?></h5>

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

                        <p class="card-text" style="color: black">
                            Publié le <small class="text-muted"><?= get_the_date(); ?></small> par <?php the_author(); ?>
                        </p>

                        <a class="linkBtn" href="<?php the_permalink(); ?>">
                            <button class="btn btnInfo">Voir la recette</button>
                        </a>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        echo paginate_links(array(
            'total' => $loop->max_num_pages,
            'current' => $paged,
            'prev_text' => '<span class="previous">Précédent</span>',
            'next_text' => '<span class="next">Suivant</span>',
			'format' => 'recipe?paged=%#%',
        ));
        ?>
    </div>

<?php endif; wp_reset_postdata(); ?>

<?php get_footer(); ?>