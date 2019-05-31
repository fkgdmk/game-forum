<?php
if (isset($_GET['gameId'])) {
    $gameId = $_GET['gameId'];
    $db = new DB();
    $connection = $db->connect_to_db();

    if (isset($_POST['delete_comment'])) {
        if (isset($_POST['comment_id'])) {
            $delete_query = $connection->prepare("DELETE FROM comment WHERE id = ?");
            $delete_query->bind_param("i", $_POST['comment_id']);
            $delete_query->execute();
            $delete_query->close();
        }
    }

    if (!empty($_POST['comment'])) {
        $comment = mysqli_real_escape_string($connection, $_POST['comment']);
        $stmt = $connection->prepare("INSERT INTO comment (user_id, game_id, content) 
                                    VALUES (?, ?, ?)");

        $stmt->bind_param("iis", $_SESSION['user_id'], $_GET['gameId'], $comment);
        $stmt->execute();
    }

    $game_query = "SELECT * 
                    FROM game 
                    WHERE game.id = $gameId";
    $game_result = $connection->query($game_query);

    $comment_query = "SELECT COMMENT.id AS comment_id, user.id AS user_id, user.nickname AS user_nickname, 
                        COMMENT.user_id, game_id, COMMENT.content AS comment_content
                        FROM COMMENT 
                        JOIN user ON user.id = COMMENT.user_id 
                        WHERE game_id = $gameId";

    $comment_result = $connection->query($comment_query);
    $connection->close();
    $game = mysqli_fetch_assoc($game_result);
}