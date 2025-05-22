document.addEventListener('DOMContentLoaded', function() {
    const replyLinks = document.querySelectorAll('.comment-reply-link');
    const commentForm = document.getElementById('commentform');
    const commentParentInput = document.getElementById('comment_parent');

    replyLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const commentId = this.getAttribute('data-commentid');
            commentParentInput.value = commentId;

            const commentElement = document.getElementById('comment-' + commentId);
            if (commentElement && commentForm) {
                commentElement.appendChild(commentForm);
            }
        });
    });
});