<?php
function create_comments($comments)
{
    ?>
    <div class="comments-container">
        <h5>Comments</h5>
        <hr>
        <?php while ($comment = $comments->fetch_assoc()) : ?>
            <div class="comments">
                <div style="display: flex; justify-content: space-between">
                    <h6><?= htmlspecialchars($comment["user_nickname"]) ?></h6>
                    <?php if (($comment['user_id'] == $_SESSION['user_id']) || isset($_SESSION['isAdmin'])) : ?>
                        <form method="POST">
                            <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>" />
                            <input type="submit" class="comment-delete" name="delete_comment" value="&times;" style="background-color: white; border: 0">
                        </form>
                    <?php endif; ?>
                </div>
                <p><?= htmlspecialchars($comment["comment_content"]) ?></p>
                <hr>
            </div>
        <?php endwhile; ?>
    </div>
<?php
}
