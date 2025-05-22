<?php
require_once __DIR__ . '/../service/utils.php';

function handle_recipe_search() {
    if (isset($_POST['query']) && strlen($_POST['query']) > 1) {
        $search_query = sanitize_text_field($_POST['query']);

        global $wpdb;

        $query = "
            SELECT ID, post_title, post_excerpt, guid
            FROM $wpdb->posts
            WHERE post_type = 'recipes'
            AND post_status = 'publish'
            AND post_title LIKE %s
            LIMIT 5
        ";

        $prepared_query = $wpdb->prepare($query, '%' . $wpdb->esc_like($search_query) . '%');

        $results = $wpdb->get_results($prepared_query);

        $formatted_results = [];

        if ($results) {
            foreach ($results as $recipe) {
                $thumbnail_url = get_the_post_thumbnail_url($recipe->ID, 'thumbnail');
                $difficulty = get_post_meta($recipe->ID, '_difficulty', true);
                $cost = get_post_meta($recipe->ID, '_cost', true);
                $duration = str_replace(":", "h", get_post_meta($recipe->ID, '_duration', true));
                $meal_type = get_the_terms($recipe->ID, 'meal_type');
				$meal_type_names = !empty($meal_type) ? array_map(function($term) { return $term->name; }, $meal_type) : [];

				$meal_type_conversion_name = [
												"breakfast" => "Petit-déjeuner",
												"lunch" => "Déjeuner",
												"snack" => "Goûter",
												"dinner" => "Dîner",
												"totry" => "À essayer"
											];
                                                
                $meal_type_names_french = [];                            

                foreach ($meal_type_names as $term_name)
                {
				    $meal_type_names_french []= esc_html(get_value_from_key($meal_type_conversion_name, $term_name));
                }

                $formatted_results[] = [
                    'message' => [
                        'title' => esc_html($recipe->post_title),
                        'meal_types' => $meal_type_names_french,
                        'url' => get_permalink($recipe->ID),
                        'image_url' => $thumbnail_url,
                        'difficulty' => esc_html($difficulty),
                        'cost' => esc_html($cost),
                        'duration' => esc_html($duration),
                    ]
                ];
            }
        } else {
            $formatted_results = ['message' => 'Aucune recette trouvée.'];
        }

        wp_send_json_success($formatted_results);
        wp_die();
    }

    wp_send_json_error();
    wp_die();
}

add_action('wp_ajax_search_recipes', 'handle_recipe_search');
add_action('wp_ajax_nopriv_search_recipes', 'handle_recipe_search');
