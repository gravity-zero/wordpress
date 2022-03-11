<?php 

add_action('after_setup_theme',
    function () {
        add_theme_support('title-tag');
    });


    add_action('wp_enqueue_scripts', function () {
        wp_enqueue_style('ofourno-bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
        wp_enqueue_script('ofourno-bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', [], false, true);
    });