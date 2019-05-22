<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

spl_autoload_register(function ($class_name) {
    include "database/" . $class_name . '.php';
});

include "partials/navbar.php";
include "partials/comments.php";

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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/game.css">
    <title>Game</title>
</head>

<body>
    <?php create_navbar()?>
    <div class="container">
        <div class="game-header">
            <h3><?= htmlspecialchars($game['title']); ?></h3>
        </div>
        <b>Release Year</b>
        <p><?= htmlspecialchars($game['year']); ?></p>
        <b>Genre</b>
        <p><?= htmlspecialchars($game['genre']); ?></p>
        <b>Description</b>
        <p><?= htmlspecialchars($game['description']); ?></p>
        <?php
        create_comments($comment_result);
        ?>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Write Comment</label>
            <form method="post">
                <textarea class="form-control" name="comment" id="exampleFormControlTextarea1" rows="3"></textarea>
                <input type="hidden" name="gameId" value="<?= $_GET['gameId']; ?>" />
                <!-- <input type="hidden" name="token" value="<?php
                                                                
                                                                ?>"> -->
                <input class="btn btn-dark" type="submit" role="button" value="Post">
            </form>
        </div>
    </div>
</body>

</html>