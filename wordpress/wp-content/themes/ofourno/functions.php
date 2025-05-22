<?php

require_once __DIR__ ."/errors_handler.php";
require_once __DIR__ ."/class/Datas_checker/Datas_checker.php";
require_once __DIR__ ."/wp_db_handler.php";

require_once __DIR__ ."/controller/init.php";
require_once __DIR__ ."/controller/register.php";
require_once __DIR__ ."/controller/login.php";
require_once __DIR__ ."/controller/recipe.php";
require_once __DIR__ ."/controller/search.php";
require_once __DIR__ ."/controller/comment.php";

//require_once __DIR__ ."/controller/newsletter.php";

add_action( 'init', 'init_theme' );
add_action( 'after_setup_theme', 'ofourno_theme_support' );

add_action( 'wp_enqueue_scripts', 'ofourno_enqueue_assets' );

add_action( 'login_form_login', function() {
    wp_redirect( site_url('/login') );
    exit;
});

add_action( 'login_form_register', function() {
    wp_redirect( site_url('/register') );
    exit;
});

add_action( 'template_redirect', function() {
    if ( get_query_var('login_treatment') ) {
        login_treatment($_POST);
        exit;
    }
    if ( get_query_var('register_treatment') ) {
        register_treatment($_POST);
        exit;
    }
});

add_action( 'admin_post_nopriv_login_treatment', 'login_treatment' );
add_action( 'admin_post_login_treatment', 'login_treatment' );

add_action( 'admin_post_nopriv_register_treatment', 'register_treatment' );
add_action( 'admin_post_register_treatment', 'register_treatment' );

add_action('admin_post_new_recipe_form', 'handle_create_recipe_form');
add_action('admin_post_edit_recipe_form', 'handle_edit_recipe_form');

add_filter('post_row_actions', function($actions, $post) {
    if ($post->post_type == 'recipes') {
        if (isset($actions['edit'])) {
            $actions['edit'] = '<a href="' . home_url('/edit-recipe?post_id=' . $post->ID) . '">Modifier</a>';
        }

        if (isset($actions['inline hide-if-no-js'])) {
            $actions['inline hide-if-no-js'] = str_replace('inline hide-if-no-js', 'edit-inline', $actions['inline hide-if-no-js']);
        }
    }
    return $actions;
}, 10, 2);


add_action('admin_post_recipe_comment_form', 'recipe_comment');
add_action('admin_post_nopriv_recipe_comment_form', 'recipe_comment');