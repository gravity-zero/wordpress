<?php

require_once __DIR__ .'/../service/media.php';
require_once __DIR__ .'/../service/file.php';

function handle_create_recipe_form() {
    if (!wp_verify_nonce($_POST['random_nonce'], 'random_action')) {
        die("C'est pas beau de ne pas passer par le formulaire");
    }

    if (!is_user_logged_in()) {
        die("Tu n'as pas les droits pour effectuer cette action");
    }

    $recipe_name = sanitize_text_field($_POST['name']);
    $difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : "⭐";
    $cost = isset($_POST['cost']) ? $_POST['cost'] : "€";
    $ingredients = isset($_POST['ingredient']) ? array_map('sanitize_text_field', $_POST['ingredient']) : [];
    $quantities = isset($_POST['quantity']) ? $_POST['quantity'] : [];
    $units = isset($_POST['unit']) ? $_POST['unit'] : [];
    $steps = isset($_POST['steps']) ? array_map('sanitize_text_field', $_POST['steps']) : [];
    $duration = isset($_POST['duration']) ? sanitize_text_field($_POST['duration']) : '00:00';
    $meal_type = isset($_POST['meal_type']) ? $_POST['meal_type'] : [];

    $post_args = [
        'post_type'       => 'recipes',
        'post_title'      => $recipe_name,
        'post_content'    => implode("\n", $steps),
        'post_status'     => 'publish',
        'post_author'     => get_current_user_id(),
        'post_name'       => sanitize_title($recipe_name),
    ];

    $post_id = wp_insert_post($post_args, true);

    if (is_wp_error($post_id)) {
        die("Erreur lors de l'enregistrement de la recette.");
    }

    if (!empty($meal_type)) {
        wp_set_object_terms($post_id, $meal_type, 'meal_type');
    }

    update_post_meta($post_id, '_duration', $duration);

    if (!empty($ingredients)) {
        $ingredients_data = [];
        foreach ($ingredients as $index => $ingredient) {
            if (!empty($ingredient) && isset($quantities[$index]) && isset($units[$index])) {
                $ingredients_data[] = [
                    'ingredient' => $ingredient,
                    'quantity'   => $quantities[$index],
                    'unit'       => $units[$index],
                ];
            }
        }
        
        update_post_meta($post_id, '_ingredients', $ingredients_data);
    }

    update_post_meta($post_id, '_difficulty', $difficulty);
    update_post_meta($post_id, '_cost', $cost);

    upload_recipe_images($post_id);

    wp_redirect(get_permalink($post_id));
    die();
}

function handle_edit_recipe_form() {
    if (!isset($_POST['random_nonce']) || !wp_verify_nonce($_POST['random_nonce'], 'random_action')) {
        die("Nonce invalide");
    }

    if (!is_user_logged_in()) {
        die("Tu n'as pas les droits pour effectuer cette action");
    }

    $recipe_id = isset($_POST['recipe_id']) ? intval($_POST['recipe_id']) : 0;

    if (!$recipe_id) {
        die("Recette invalide");
    }

    $recipe_name = sanitize_text_field($_POST['name']);
    $steps = isset($_POST['steps']) ? array_map('sanitize_text_field', $_POST['steps']) : [];
    $quantities = isset($_POST['quantity']) ? $_POST['quantity'] : [];
    $units = isset($_POST['unit']) ? $_POST['unit'] : [];
    $duration = isset($_POST['duration']) ? sanitize_text_field($_POST['duration']) : '';
    $meal_type = isset($_POST['meal_type']) ? $_POST['meal_type'] : [];

    $post_args = [
        'ID'            => $recipe_id,
        'post_title'    => $recipe_name,
        'post_content'  => trim(implode("\n", $steps)),
        'post_status'   => 'publish',
    ];

    $post_id = wp_update_post($post_args, true);

    if (is_wp_error($post_id)) {
        die("Erreur lors de la mise à jour de la recette.");
    }

    if (!empty($meal_type)) {
        wp_set_object_terms($post_id, $meal_type, 'meal_type');
    }

    if($duration){
        update_post_meta($post_id, '_duration', $duration);
    }

    if(isset($_POST['difficulty'])){
        update_post_meta($post_id, '_difficulty', $_POST['difficulty']);
    }
    
    if(isset($_POST['cost'])){
        update_post_meta($post_id, '_cost', $_POST['cost']);
    }
    
    if(isset($_POST['ingredient'])){
        $ingredients = array_map('sanitize_text_field', $_POST['ingredient']);
        $ingredients_data = [];
        foreach ($ingredients as $index => $ingredient) {
            if (!empty($ingredient) && isset($quantities[$index]) && isset($units[$index])) {
                $ingredients_data[] = [
                    'ingredient' => $ingredient,
                    'quantity'   => $quantities[$index],
                    'unit'       => $units[$index],
                ];
            }
        }
        update_post_meta($post_id, '_ingredients', $ingredients_data);
    }
    
    upload_recipe_images($post_id, true);

    wp_redirect(get_permalink($post_id));
    exit;
}