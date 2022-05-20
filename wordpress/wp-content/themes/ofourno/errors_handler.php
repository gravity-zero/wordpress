<?php

function print_error_message($error): void
{
    echo "<div id='error_message'><h1>". $error ."</h1></div>";
}

add_action( 'template_redirect', 'get_custom_404' );

function get_custom_404() {
    if ( is_404() ) {
        add_action( 'wp_enqueue_scripts', function () {
            wp_enqueue_style( 'ofourno-404-css', get_stylesheet_directory_uri() . '/assets/css/404.css' );
            wp_enqueue_script( 'ofourno-404-css', get_stylesheet_directory_uri() . '/assets/js/404.js', [], false, true );
        } );
    }
}