<?php

function register_treatment(){
    if ( !isset($_POST['register_nonce']) || !wp_verify_nonce($_POST['register_nonce'], 'register_action') ) {
        wp_die('Nonce de sécurité invalide.');
    }

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
        "role" => "user"
    ]);

    if(is_wp_error($user)) {
        print_error_message($user->get_error_message());
        die();
    }
    
    wp_redirect(get_home_url(). "/login");
    die();
}
