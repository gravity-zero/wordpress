<?php

require_once "errors_handler.php";
require_once "class/Datas_checker/Datas_checker.php";
require_once "wp_db_handler.php";
require_once "newsletter.php";

add_action("init", function()
{
    init_theme();
});

add_action('after_setup_theme', function () {
    add_theme_support( 'title-tag' );
});

add_action( 'wp_head', function () {
    echo '<link rel="icon" type="image/png" href="' . get_stylesheet_directory_uri() . '/assets/images/favicon.png"/>';
});

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'ofourno-bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' );
    wp_enqueue_script( 'ofourno-bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', [], false, true );
    wp_enqueue_style( 'ofourno-custom-css', get_stylesheet_directory_uri() . '/assets/styles/style.css' );
    wp_enqueue_style( 'ofourno-404-css', get_stylesheet_directory_uri() . '/assets/styles/global/404.css' );
});

add_action('after_switch_theme', function(){
    add_role('moderator', 'moderator', [
        'manage_events' => true,
        'read'  => true,
        'delete_posts'  => true,
        'delete_published_posts' => true,
        'edit_posts'   => true,
        'publish_posts' => true,
        'upload_files'  => true,
        'edit_pages'  => true,
        'edit_published_pages'  =>  true,
        'publish_pages'  => true,
        'delete_published_pages' => false
    ]);

    flush_rewrite_rules();
});

function ofourno_theme_support() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    if (!current_user_can('subscriber') && !is_admin()) {
        show_admin_bar( false );
    }
}


function login_treatment($datas){
    if ($datas) {
        $login = wp_signon($datas);
        if (is_wp_error($login)) {
            print_error_message($login->get_error_message());
            die();
        }
    }

    if (current_user_can("subscriber") && !is_admin()) {
        add_filter("show_admin_bar", "__return_false");
    }

    wp_redirect(get_home_url());
    die();
}

function register_treatment($datas){

    if($datas)
    {
        $control_test = [
            "user_login" => ["required", "is_string", "min_lenght" => "2", "error_message" => "Login est manquant ou incorrect"],
            "user_email" => ["required", "is_email", "disposable_email", "error_message" => "Email incorrect ou manquant"],
            "user_pass" => ["required", "min_lenght" => "6", "error_message" => "Le mot de passe est vide ou inférieur à 6 caractères"]
                            ];
        $data_check = new Datas_checker();
        $isCorrectDatas = $data_check->check([$_POST], $control_test);
        if(is_array($isCorrectDatas)){ var_dump($isCorrectDatas); die();}

        $login = $_POST["user_login"];
        $email = $_POST["user_email"];
        $password = $_POST["user_pass"];

        if($password !== $_POST["user_pass_check"]) {
            var_dump("Les Mots de passes ne sont pas identique");
            die();
        }

        $user = wp_insert_user([
            "user_pass" => $password,
            "user_login" => $login,
            "user_email" => $email,
            "role" => "subscriber"
        ]);

        if(is_wp_error($user)) {
            print_error_message($user->get_error_message());
            die();
        }
    }
    wp_redirect(get_home_url(). "/login");
    die();
}

add_action('admin_post_new_recipe_form', function () {
    if (!wp_verify_nonce($_POST['random_nonce'], 'random_action')) {
        die("C'est pas beau de ne pas passer par le formulaire");
    }

    if (!is_user_logged_in()) {
        die("Tu n'as pas les droits pour effectuer cette action");
    }

    // Récupérer les données du formulaire
    $recipe_name = sanitize_text_field($_POST['name']);
    $difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : "⭐";
    $cost = isset($_POST['cost']) ? $_POST['cost'] : "€";
    $ingredients = isset($_POST['ingredient']) ? array_map('sanitize_text_field', $_POST['ingredient']) : [];
    $quantities = isset($_POST['quantity']) ? $_POST['quantity'] : [];
    $units = isset($_POST['unit']) ? $_POST['unit'] : [];
    $steps = isset($_POST['steps']) ? array_map('sanitize_text_field', $_POST['steps']) : [];

    // Créer le post
    $post_args = [
        'post_type'       => 'recipes',
        'post_title'      => $recipe_name,
        'post_content'    => implode("\n", $steps),
        'post_status'     => 'publish',
        'post_author'     => get_current_user_id(),
        'post_name'       => sanitize_title($recipe_name),
    ];

    // Insérer le post
    $postId = wp_insert_post($post_args, true);

    if (is_wp_error($postId)) {
        die("Erreur lors de l'enregistrement de la recette.");
    }

    // Ajouter les ingrédients comme des meta-données
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
        
        update_post_meta($postId, '_ingredients', $ingredients_data);
    }

    update_post_meta($postId, '_difficulty', $difficulty);
    update_post_meta($postId, '_cost', $cost);

    if (isset($_FILES['images'])) {
        $images = $_FILES['images'];

        for ($i = 0; $i < count($images['name']); $i++) {
            if ($images) {
                $file = array(
                    'name'     => $images['name'][$i],
                    'type'     => $images['type'][$i],
                    'tmp_name' => $images['tmp_name'][$i],
                    'error'    => $images['error'][$i],
                    'size'     => $images['size'][$i]
                );

                $_FILES = ["my_file_upload" => $file];
                
                foreach ($_FILES as $file => $array) {
                    if ($file) {
                        $image_id = media_handle_upload($file, $postId);
                        if (is_wp_error($image_id)) {
                            print_error_message($image_id->get_error_message());
                            die();
                        }
                        if (!(int)$image_id) die("We have a situation :o ");

                        set_post_thumbnail($postId, $image_id);
                    }
                }
            }
        }
    }

    wp_redirect(get_permalink($postId));
    die();
});

add_action('admin_post_edit_recipe_form', 'handle_edit_recipe_form');

function handle_edit_recipe_form() {
    // Vérification du nonce
    if (!isset($_POST['random_nonce']) || !wp_verify_nonce($_POST['random_nonce'], 'random_action')) {
        die("Nonce invalide");
    }

    // Vérification de l'utilisateur
    if (!is_user_logged_in()) {
        die("Tu n'as pas les droits pour effectuer cette action");
    }

    // Récupérer l'ID de la recette à modifier
    $recipe_id = isset($_POST['recipe_id']) ? intval($_POST['recipe_id']) : 0;

    if (!$recipe_id) {
        die("Recette invalide");
    }

    // Récupérer les nouvelles données
    $recipe_name = sanitize_text_field($_POST['name']);
    $difficulty = sanitize_text_field($_POST['difficulty']);
    $cost = sanitize_text_field($_POST['cost']);
    $steps = isset($_POST['steps']) ? array_map('sanitize_text_field', $_POST['steps']) : [];
    $ingredients = isset($_POST['ingredient']) ? array_map('sanitize_text_field', $_POST['ingredient']) : [];
    $quantities = isset($_POST['quantity']) ? $_POST['quantity'] : [];
    $units = isset($_POST['unit']) ? $_POST['unit'] : [];

    // Mise à jour du titre de la recette
    $post_args = [
        'ID'            => $recipe_id,
        'post_title'    => $recipe_name,
        'post_content'  => implode("\n", $steps), // Récupérer les étapes
        'post_status'   => 'publish',  // Ou 'pending' selon le statut voulu
    ];

    // Mettre à jour la recette
    $post_id = wp_update_post($post_args, true);

    if (is_wp_error($post_id)) {
        die("Erreur lors de la mise à jour de la recette.");
    }

    // Mettre à jour les métadonnées
    update_post_meta($post_id, '_difficulty', $difficulty);
    update_post_meta($post_id, '_cost', $cost);
    
    // Mettre à jour les ingrédients
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

    // Mettre à jour les étapes
    update_post_meta($post_id, '_steps', $steps);

    // Rediriger vers la page de la recette modifiée
    wp_redirect(get_permalink($post_id));
    exit;
}

function init_theme () {

    $labels = array(
        // Le nom au pluriel
        'name'                => 'recipes',
        // Le nom au singulier
        'singular_name'       => 'recipe',
        // Le libellé affiché dans le menu
        'menu_name'           => 'Modération recettes',
        // Les différents libellés de l'administration
        'all_items'           => 'Toutes les recettes',
        'view_item'           => 'Voir les recettes',
        'add_new_item'        => 'Ajouter une nouvelle une recette',
        'add_new'             => 'Ajouter',
        'edit_item'           => 'Editer une recette',
        'update_item'         => 'Modifier une recette',
        'search_items'        => 'Rechercher une recette',
        'not_found'           => 'Non trouvée',
        'not_found_in_trash'  => 'Non trouvée dans la corbeille'
    );

    $postArgs = [
        'label'           => 'recipes',
        'labels'          => $labels,
        'public'          => true,
        'show_in_rest'    => true,
        'capability_type' => 'post',
        'has_archive'     => true,
        'supports'        => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'rewrite'		  => array( 'slug' => 'recipes')
    ];

    register_post_type('recipes', $postArgs);
};

function recipe_comment() {
    //var_dump($_POST);
    die();

    if (!wp_verify_nonce($_POST['random_nonce'], 'random_action')){
        die("C'est pas beau de ne pas passer par le formulaire");
    }
    //wp_new_comment();
    if( $data['post_type'] == 'recipe_comment_form' ) {
        $data['comment_status'] = 1;
    }

    return $data;
}

add_filter('post_row_actions', function($actions, $post) {
    // Vérifier que c'est bien une recette
    if ($post->post_type == 'recipes') {
        // Personnaliser le lien de modification
        if (isset($actions['edit'])) {
            $actions['edit'] = '<a href="' . home_url('/edit-recipe?post_id=' . $post->ID) . '">Modifier</a>';
        }

        // Personnaliser le lien de modification rapide si nécessaire
        if (isset($actions['inline hide-if-no-js'])) {
            $actions['inline hide-if-no-js'] = str_replace('inline hide-if-no-js', 'edit-inline', $actions['inline hide-if-no-js']);
        }
    }
    return $actions;
}, 10, 2);


add_action('admin_post_recipe_comment_form', 'recipe_comment');

add_action('admin_post_nopriv_recipe_comment_form', 'recipe_comment');

add_action("load-post-new.php", function(){
    switch($_GET["post_type"])
    {
        case "recipes":
            wp_redirect(get_home_url(). "/add-recipe");
            break;
        case "login":
            wp_redirect(get_home_url() . "/login");
            break;
        case "register":
            wp_redirect(get_home_url(). "/register");
            break;
    }
});

add_action("post-new.php", function(){
   if($_GET["post_type"] === "recipes"){
    wp_redirect(get_home_url(). "/add-recipe");
    die;
   }
});

//
switch($_SERVER["REQUEST_URI"]) {
    case "/login-treatment":
        login_treatment($_POST);
        break;
    case "/register-treatment":
        register_treatment($_POST);
        break;
    case "wp-login.php":
        if (isset($_GET) && $_GET["action"] === "register") {
            wp_redirect(get_home_url(). "/register");
        } else {
            var_dump("login");
            die();
            wp_redirect(get_home_url() . "/login");
        }
        break;
}
