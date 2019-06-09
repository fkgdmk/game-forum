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

    //Get comment from db
    if (!empty($_POST['comment'])) {
        $comment = mysqli_real_escape_string($connection, $_POST['comment']);
        $stmt = $connection->prepare("INSERT INTO comment (user_id, game_id, content) 
                                    VALUES (?, ?, ?)");

        $stmt->bind_param("iis", $_SESSION['user_id'], $_GET['gameId'], $comment);
        $stmt->execute();
    }

    $game_query = "SELECT * 
                    FROM game 
                    WHERE game.id = ?";
    $stmt2 = $connection->prepare($game_query);
    $stmt2->bind_param("i", $gameId);
    $stmt2->execute();
    $game_result = $stmt2->get_result();

    $comment_query = "SELECT COMMENT.id AS comment_id, user.id AS user_id, user.nickname AS user_nickname, 
                        COMMENT.user_id, game_id, COMMENT.content AS comment_content
                        FROM COMMENT 
                        JOIN user ON user.id = COMMENT.user_id 
                        WHERE game_id = ?";
    $stmt3 = $connection->prepare($comment_query);
    $stmt3->bind_param("i", $gameId);
    $stmt3->execute();
    $comment_result = $stmt3->get_result();
    $connection->close();
    $game = mysqli_fetch_assoc($game_result);
}