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
    wp_enqueue_style( 'ofourno-404-css', get_stylesheet_directory_uri() . '/assets/css/404.css' );
    wp_enqueue_script( 'ofourno-404-js', get_stylesheet_directory_uri() . '/assets/js/404.js', [], false, true );
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

add_action('admin_post_new_recette_form', function () {
	if (!wp_verify_nonce($_POST['random_nonce'], 'random_action')){
		die("C'est pas beau de ne pas passer par le formulaire");
	}
	//if(!current_user_can('manage_events')) die("Tu n'as pas les droits pour effectuer cette action");

	$post_args = [
        'post_type'       => 'recette',
		'post_title'      => $_POST['title'],
		'post_content'    => $_POST['content'],
		'post_status'     => 'pending',
		'post_ingredient' => $_POST['ingredient'],
		'post_author'     => get_current_user_id()
	];

	$postId = wp_insert_post( $post_args );

	$images = $_FILES['images'];
    //IL FAUDRAIT RENOMMER LES IMAGES UPLOADÉES
		for($i=0; $i < count($images['name']); $i++) {
			if ($images) {
				$file = array(
					'name' => $images['name'][$i],
					'type' => $images['type'][$i],
					'tmp_name' => $images['tmp_name'][$i],
					'error' => $images['error'][$i],
					'size' => $images['size'][$i]
				);
                $_FILES = ["my_file_upload" => $file];
				foreach ($_FILES as $file => $array) {
                    if($file){
                        $image_id = media_handle_upload($file, $postId);
                        if(is_wp_error($image_id)){ print_error_message($image_id->get_error_message()); die();}
                        if(!(int)$image_id) die("We have a situation :o ");

                        set_post_thumbnail($postId, $image_id); //La dernière image sera l'image par défaut
                    }
				}
			}
		}
    wp_redirect( get_post_permalink( $postId ) );
        die();
});

function init_theme () {

    $labels = array(
        // Le nom au pluriel
        'name'                => 'Recettes',
        // Le nom au singulier
        'singular_name'       => 'Recette',
        // Le libellé affiché dans le menu
        'menu_name'           => 'Modération Recettes',
        // Les différents libellés de l'administration
        'all_items'           => 'Toutes les Recettes',
        'view_item'           => 'Voir les Recettes',
        'add_new_item'        => 'Ajouter une nouvelle une Recette',
        'add_new'             => 'Ajouter',
        'edit_item'           => 'Editer une Recette',
        'update_item'         => 'Modifier une Recette',
        'search_items'        => 'Rechercher une Recette',
        'not_found'           => 'Non trouvée',
        'not_found_in_trash'  => 'Non trouvée dans la corbeille'
    );

    $postArgs = [
        'label'           => 'Recettes',
        'labels'          => $labels,
        'public'          => true,
        'show_in_rest'    => true,
        'capability_type' => 'post',
        'has_archive'     => true,
        'supports'        => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'rewrite'		  => array( 'slug' => 'Recettes')
    ];

    register_post_type('recette', $postArgs);
};

add_action("load-page-new.php", function(){
    switch($_GET["post_type"])
    {
        case "recette":
            wp_redirect(get_home_url(). "/ajouter-recette");
            break;
        case "login":
            wp_redirect(get_home_url() . "/login");
            break;
        case "register":
            wp_redirect(get_home_url(). "/register");
            break;
    }
});

switch($_SERVER["REQUEST_URI"]) {
    case "/login-treatment":
        login_treatment($_POST);
        break;
    case "/register-treatment":
        register_treatment($_POST);
        break;
    case "wp-login.php":
        if (isset($_GET) && $_GET["action"] == "register") {
            wp_redirect(get_home_url(). "/register");
        } else {
            wp_redirect(get_home_url() . "/login");
        }
        break;
}
