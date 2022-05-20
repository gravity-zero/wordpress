<?php wp_footer(); ?>

<footer class="footer-container">
    <form action="<?= admin_url('admin-post.php'); ?>" method="post">
        <input type="hidden" name="action" value="subscribe_newsletter">
        <?php wp_nonce_field('random_action', 'random_nonce'); ?>
        <?php wp_referer_field(); ?>
        <div>
            <label for="subscriber_email" class="subscriber_email_label">Inscrivez-vous à notre newsletter</label>
            <input type="email" id="subscriber_email" name="subscriber_email" class="form-control" placeholder="Email"/>
        </div>
        <div class="submit-button">
            <button type="submit" class="btn btnNewsletter">Subscribe</button>
        </div>
    </form>
</footer>

</body>
</html>
