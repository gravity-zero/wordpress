<?php
function handle_recipe_comment() {
    if ( !isset($_POST['random_nonce']) || !wp_verify_nonce($_POST['random_nonce'], 'random_action') ) {
        wp_die('Nonce de sécurité invalide.');
    }

    if ( isset($_POST['comment-message'], $_POST['comment-email'], $_POST['comment-name']) ) {
        $comment_message = sanitize_textarea_field($_POST['comment-message']);
        $comment_email = sanitize_email($_POST['comment-email']);
        $comment_name = sanitize_text_field($_POST['comment-name']);

        if ( empty($comment_message) || empty($comment_email) || empty($comment_name) ) {
            wp_die('Tous les champs sont obligatoires.');
        }

        if ( !is_email($comment_email) ) {
            wp_die('Adresse email invalide.');
        }

        $user_id = get_current_user_id();
        $user_ip = $_SERVER['REMOTE_ADDR'];

        if ( isset($_POST['recipe_id']) ) {
            $recipe_id = intval($_POST['recipe_id']);
        } else {
            wp_die('ID de la recette manquant.');
        }

        $comment_parent = isset($_POST['comment_parent']) ? intval($_POST['comment_parent']) : 0;

        $comment_data = [
            'comment_post_ID'      => $recipe_id,
            'comment_author'       => $comment_name,
            'comment_author_email' => $comment_email,
            'comment_content'      => $comment_message,
            'comment_type'         => 'comment',
            'comment_parent'       => $comment_parent,
            'user_id'              => $user_id ? $user_id : 0,
            'comment_date'         => current_time('mysql'),
            'comment_approved'     => 1,
            'comment_author_IP'    => $user_ip,
        ];

        $comment_id = wp_insert_comment($comment_data);

        if ( $comment_id ) {
            wp_redirect(get_permalink($recipe_id) . '#comments');
            exit;
        } else {
            wp_die('Erreur lors de l\'ajout du commentaire.');
        }
    } else {
        wp_die('Veuillez remplir tous les champs.');
    }
}

add_action('admin_post_recette_comment_form', 'handle_recipe_comment');
add_action('admin_post_nopriv_recette_comment_form', 'handle_recipe_comment');
?>
