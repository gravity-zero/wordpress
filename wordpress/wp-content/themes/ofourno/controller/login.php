<?php

function login_treatment(){
    if (!isset($_POST['login_nonce']) || !wp_verify_nonce($_POST['login_nonce'], 'login_action')) {
        wp_die('Nonce de sécurité invalide.');
    }

    if ($_POST) {
        $login = wp_signon($_POST);

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
