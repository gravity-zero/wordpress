
<br>
<?php var_dump($_POST);?>
<h2 class="comment-title">Commentaires: </h2>
<div class="comment-form">
    <form <?= admin_url('admin-post.php'); ?> method="post">
        <input type="hidden" name="action" value="recette_comment_form">
        <?php wp_nonce_field('random_action', 'random_nonce'); ?>
        <?php wp_referer_field(); ?>
        <label for="comment-message">Votre commentaire</label>
        <div>
            <textarea id="comment-message" name="comment-message" rows="5" cols="40"></textarea>
        </div>
        <div>
            <label for="comment-email">Votre Email</label>
            <input type="email" id="comment-email" name="comment-email" class="form-control">
        </div>
        <div>
            <label for="comment-name">Votre Nom</label>
            <input type="text" id="comment-name" name="comment-name" class="form-control">
        </div>
        <div>
            <button type="submit">Envoyer</button>
        </div>
    </form>
</div>

<div class="comments-container">
    <?php
    if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php $comments_number = get_comments_number(); ?>
            <?= get_the_title() . $comments_number; ?>
        </h2>
        <ol class="comment-list">
            <?php
            wp_list_comments([
                'avatar_size' => 100,
                'style'       => 'ol',
                'short_ping'  => true,
                'reply_text'  => __( 'Reply', 'nd_dosth' )
            ]);
            ?>
        </ol>
        <?php the_comments_pagination([
            'prev_text' => '<span class="screen-reader-text">' . __( 'Previous', 'nd_dosth' ) . '</span>',
            'next_text' => '<span class="screen-reader-text">' . __( 'Next', 'nd_dosth' ) . '</span>'
        ]);
    endif;
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php _e( 'Pas encore de commentaires :)', 'nd_dosth' ); ?></p>
    <?php endif; ?>
</div>
