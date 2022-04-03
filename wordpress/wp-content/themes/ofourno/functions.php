<?php 

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
});

add_action('wp_head', function () {
   echo '<link rel="icon" type="image/png" href="'. get_stylesheet_directory_uri() .'assets/images/favicon.png"/>';
});


add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('ofourno-bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
    wp_enqueue_script('ofourno-bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', [], false, true);
});

add_filter('login_headerurl', function ($header_url)
    {
        var_dump($header_url);
        return 'https://www.google.fr';
    }
);

if (current_user_can('subscriber') && !is_admin())
{
    show_admin_bar(false);
}

function ofourno_theme_support()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
