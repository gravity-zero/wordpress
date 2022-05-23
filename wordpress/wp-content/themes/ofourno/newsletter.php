<?php

//Not connected
add_action('admin_post_nopriv_subscribe_newsletter', 'subscribe_newsletter');
//connected
add_action('admin_post_subscribe_newsletter', 'subscribe_newsletter');

function subscribe_newsletter()
{
    if (!wp_verify_nonce($_POST['random_nonce'], 'random_action')){
        die("C'est pas beau de ne pas passer par le formulaire");
    }

    $control_test = ["subscriber_email" => ["required", "is_email", "disposable_email", "error_message" => "Email incorrect"]];
    $data_check = new Datas_checker();
    $isCorrectDatas = $data_check->check([$_POST], $control_test);
    if(is_array($isCorrectDatas)){ var_dump($isCorrectDatas); die();}

    global $wpdb;

    $table_name = $wpdb->prefix."newsletter";
    $charset_collate = $wpdb->get_charset_collate();

    $args = new stdClass();
    $args->id = ["mediumint(9)", "NOT NULL", "AUTO_INCREMENT"];
    $args->email = ["varchar(255)", "DEFAULT ''", "NOT NULL"];
    $args->date = ["datetime", "DEFAULT '0000-00-00'", "NOT NULL"];
    $args->active = ["tinyint", "DEFAULT '1'", "NOT NULL"];
    $args->primary = ["(id)"];

    $dateTime = new dateTime();

    create_table_if_not_exist($table_name, $args, $charset_collate);

    //TODO check email exist

    $db_result = $wpdb->insert(
        $table_name,
        array("email" => $_POST["subscriber_email"],
            "date" => $dateTime->format('Y-m-d H:i:s'))
    );

    if(!$db_result) echo "Une erreur est survenu, nous n'avons pas réussi à vous inscrire, merci de réessayer ultérieurement";

    //TODO Show register confirmation + send email
    wp_redirect(wp_get_referer() ?: get_home_url());
}

//TODO Unsubscibe
//TODO Global boolean for display subscribe banner