<hr class="comment-separation">

<h2 class="comment-title">Commentaires :</h2>

<div class="comment-form">
    <form action="<?= admin_url('admin-post.php'); ?>" method="post" class="row" id="commentform">
        <input type="hidden" name="action" value="recette_comment_form">
        <input type="hidden" name="recipe_id" value="<?= get_the_ID(); ?>">
        <input type="hidden" name="comment_parent" id="comment_parent" value="0">
        <?php wp_nonce_field('random_action', 'random_nonce'); ?>
        <?php wp_referer_field(); ?>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="comment-name">Pseudo</label>
                <input type="text" id="comment-name" name="comment-name" class="form-control" placeholder="Votre Pseudo">
            </div>

            <div class="form-group mb-3">
                <label for="comment-email">Email</label>
                <input type="email" id="comment-email" name="comment-email" class="form-control" placeholder="exemple@mail.com">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="comment-message">Votre commentaire</label>
                <textarea id="comment-message" name="comment-message" rows="5" class="form-control" placeholder="Partagez vos pensées..."></textarea>
            </div>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary w-100">Envoyer</button>
        </div>
    </form>
</div>

<div class="comments-container">
    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php $comments_number = get_comments_number(); ?>
            <?= get_the_title() . ' - ' . $comments_number . ' Commentaire(s)'; ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments([
                'avatar_size' => 100,
                'style'       => 'ol',
                'short_ping'  => true,
                'reply_text'  => __( 'Répondre', 'nd_dosth' ),
                'callback'    => function($comment, $args, $depth) {
                    $GLOBALS['comment'] = $comment;
                    ?>
                    <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                        <div class="comment-body">
                            <div class="comment-author vcard">
                                <?php echo get_avatar($comment, 100); ?>
                                <cite class="fn"><?php comment_author_link(); ?></cite>
                                <span class="says">dit :</span>
                            </div>
                            <div class="comment-meta commentmetadata">
                                <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
                                    <?php printf( __('%1$s à %2$s'), get_comment_date(),  get_comment_time() ); ?>
                                </a>
                                <?php edit_comment_link( '(Modifier)', '  ', '' ); ?>
                            </div>

                            <div class="comment-content">
                                <?php comment_text(); ?>
                            </div>

                            <div class="reply">
                                <a href="#" class="comment-reply-link" data-commentid="<?php comment_ID(); ?>">Répondre</a>
                            </div>
                        </div>
                    </li>
                    <?php
                },
            ]);
            ?>
        </ol>

        <?php the_comments_pagination([
            'prev_text' => '<span class="screen-reader-text">' . __( 'Précédent', 'nd_dosth' ) . '</span>',
            'next_text' => '<span class="screen-reader-text">' . __( 'Suivant', 'nd_dosth' ) . '</span>'
        ]);
    endif;

    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="no-comments"><?php _e( 'Pas encore de commentaires :)', 'nd_dosth' ); ?></p>
    <?php endif; ?>
</div>