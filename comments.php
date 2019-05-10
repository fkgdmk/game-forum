<?php
function create_comments($comments)
{
    ?>
    <div class="comments-container">
        <h5>Comments</h5>
        <hr>
        <?php while ($comment = $comments->fetch_assoc()) : ?>
            <div class="comments">
                <h6><?= $comment["user_nickname"] ?></h6>
                <p><?= $comment["comment_content"]?></p>
                <hr>
            </div>
        <?php endwhile; ?>
    </div>
<?php
}
