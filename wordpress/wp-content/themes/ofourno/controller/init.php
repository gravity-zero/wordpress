<?php

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

    add_rewrite_rule(
        '^login-treatment/?$',
        'index.php?login_treatment=1',
        'top'
    );
    add_rewrite_rule(
        '^register-treatment/?$',
        'index.php?register_treatment=1',
        'top'
    );
    add_rewrite_tag( '%login_treatment%',    '([01])' );
    add_rewrite_tag( '%register_treatment%', '([01])' );

    create_recipe_taxonomy();
    create_custom_pages();
};

function create_custom_pages() {
    $pages = [
        'add-recipe'   => 'page-add-recipe',
        'edit-recipe'  => 'page-edit-recipe',
        'login'        => 'page-login',
        'register'     => 'page-register',
        'recipe'       => 'single-recipes',
    ];

    foreach ($pages as $slug => $title) {
        $page_check = get_page_by_path($slug, OBJECT, 'page');

        if (empty($page_check)) {
            $new_page = array(
                'post_title'    => $title,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
                'post_name'     => $slug,
            );

            wp_insert_post($new_page);
        }
    }
}

function create_recipe_taxonomy() {
    $args = array(
        'hierarchical' => true,
        'labels' => array(
            'name'              => 'Types de repas',
            'singular_name'     => 'Type de repas',
            'search_items'      => 'Rechercher des types de repas',
            'all_items'         => 'Tous les types de repas',
            'parent_item'       => 'Type de repas parent',
            'parent_item_colon' => 'Type de repas parent :',
            'edit_item'         => 'Éditer le type de repas',
            'update_item'       => 'Mettre à jour le type de repas',
            'add_new_item'      => 'Ajouter un nouveau type de repas',
            'new_item_name'     => 'Nom du nouveau type de repas',
            'menu_name'         => 'Types de repas',
        ),
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => 'meal-type',
            'with_front' => false,
            'hierarchical' => true
        ),
    );

    register_taxonomy('meal_type', 'recipes', $args);
}

function ofourno_theme_support() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );

    if (is_user_logged_in() && !current_user_can( 'administrator' )) {
        show_admin_bar( false );
    }
}

add_action( 'wp_head', function () {
    echo '<link rel="icon" type="image/png" href="' . get_stylesheet_directory_uri() . '/assets/images/favicon.png"/>';
});

// CSS & JS
function ofourno_enqueue_assets() {

    wp_enqueue_style(
        'ofourno-bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css',
        [], '5.3.6'
    );
    wp_enqueue_script(
        'ofourno-bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js',
        [], '5.3.6', true
    );

    wp_enqueue_style(
        'ofourno-style',
        get_stylesheet_directory_uri() . '/assets/styles/style.css',
        [], filemtime( get_stylesheet_directory() . '/assets/styles/style.css' )
    );

    if ( is_404() ) {
        wp_enqueue_style(
            'ofourno-404',
            get_stylesheet_directory_uri() . '/assets/styles/global/404.css',
            [ 'ofourno-style' ],
            filemtime( get_stylesheet_directory() . '/assets/styles/global/404.css' )
        );
    }

    if ( is_home() || isset($_GET['query']) || is_page('recipe')) {
        wp_enqueue_style(
            'ofourno-homepage',
            get_stylesheet_directory_uri() . '/assets/styles/pages/homepage.css',
            [ 'ofourno-style' ],
            filemtime( get_stylesheet_directory() . '/assets/styles/pages/homepage.css' )
        );
    }

    if ( is_page( 'register' ) ) {
        wp_enqueue_style(
            'ofourno-register',
            get_stylesheet_directory_uri() . '/assets/styles/pages/register.css',
            [ 'ofourno-style' ],
            filemtime( get_stylesheet_directory() . '/assets/styles/pages/register.css' )
        );
    }

    if ( is_page( 'login' ) ) {
        wp_enqueue_style(
            'ofourno-login',
            get_stylesheet_directory_uri() . '/assets/styles/pages/login.css',
            [ 'ofourno-style' ],
            filemtime( get_stylesheet_directory() . '/assets/styles/pages/login.css' )
        );
    }

    if ( is_page( 'add-recipe' ) ) {
        wp_enqueue_style(
            'ofourno-add-recipe',
            get_stylesheet_directory_uri() . '/assets/styles/pages/recipe-form.css',
            [ 'ofourno-style' ],
            filemtime( get_stylesheet_directory() . '/assets/styles/pages/recipe-form.css' )
        );
    }

    if (
        is_page( 'edit-recipe' )
        || (
            isset( $_GET['post_id'] )
            && get_post_type( intval( $_GET['post_id'] ) ) === 'recipes'
        )
    ) {
        wp_enqueue_style(
            'ofourno-edit-recipe',
            get_stylesheet_directory_uri() . '/assets/styles/pages/recipe-form.css',
            [ 'ofourno-style' ],
            filemtime( get_stylesheet_directory() . '/assets/styles/pages/recipe-form.css' )
        );
    }

    if ( is_singular( 'recipes' ) ) {
        wp_enqueue_style(
            'ofourno-single-recipe',
            get_stylesheet_directory_uri() . '/assets/styles/pages/single-recipe.css',
            [ 'ofourno-style' ],
            filemtime( get_stylesheet_directory() . '/assets/styles/pages/single-recipe.css' )
        );

        wp_enqueue_style(
            'ofourno-comments-recipe',
            get_stylesheet_directory_uri() . '/assets/styles/pages/comments.css',
            [ 'ofourno-style' ],
            filemtime( get_stylesheet_directory() . '/assets/styles/pages/comments.css' )
        );
    }

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script(
            'ofourno-comment-reply-js',
            get_theme_file_uri( 'assets/js/comment-reply.js' )
        );
    }
}

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