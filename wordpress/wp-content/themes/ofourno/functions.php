<?php

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
} );

add_action( 'wp_head', function () {
	echo '<link rel="icon" type="image/png" href="' . get_stylesheet_directory_uri() . '/assets/images/favicon.png"/>';
} );

add_action( 'admin_post_new_recette_form', function () {
	if ( ! wp_verify_nonce( $_POST['random_nonce'], 'random_action' ) ) {
		die( "C'est pas beau de ne pas passer par le formulaire" );
	}
	//if(!current_user_can('manage_events')) die("Tu n'as pas les droits pour effectuer cette action");

	$post_args = [
		'post_title'      => $_POST['title'],
		'post_content'    => $_POST['content'],
		'post_status'     => 'pending',
		'post_ingredient' => $_POST['ingredient'],
		'post_author'     => get_current_user_id()
	];

	$postId = wp_insert_post( $post_args );

	$images = $_FILES['images'];

	var_dump(count( $_FILES['images'] ));
	if ( count($_FILES['images'])  > 1) {
		$images = $_FILES['images'];
		$i=0;
		foreach ($images as $key => $image) {
			var_dump($image);
			if ($image) {
				$file = array(
					'name' => $images['name'][$i],
					'type' => $images['type'][$i],
					'tmp_name' => $images['tmp_name'][$i],
					'error' => $images['error'][$i],
					'size' => $images['size'][$i]
				);
				$upload_array = ["my_file_upload" => $file];
				foreach ($upload_array as $file => $array) {
					$image_id = media_handle_upload($file, $postId);
					update_post_meta($postId, '_my_file_upload', $image_id);
				}
			}
			$i++;
		}
	}

	wp_redirect( get_post_permalink( $postId ) );
} );

function get_custom_404() {
	if ( is_404() ) {
		add_action( 'wp_enqueue_scripts', function () {
			wp_enqueue_style( 'ofourno-404-css', get_stylesheet_directory_uri() . '/assets/css/404.css' );
			wp_enqueue_script( 'ofourno-404-css', get_stylesheet_directory_uri() . '/assets/js/404.js', [], false, true );
		} );
	}
}

add_action( 'template_redirect', 'get_custom_404' );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'ofourno-bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' );
	wp_enqueue_script( 'ofourno-bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', [], false, true );
} );

add_filter( 'login_headerurl', function () {
	return home_url();
}
);

function ofourno_theme_support() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	if ( ! current_user_can( 'subscriber' ) && ! is_admin() ) {
		show_admin_bar( false );
	}
}

add_action( 'init', function () {

	$postArgs = [
		'label'           => 'Recettes',
		'public'          => true,
		'show_in_rest'    => true,
		'capability_type' => 'post',
		'has_archive'     => true
	];

	register_post_type( 'recette', $postArgs );
} );
