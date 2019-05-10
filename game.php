<?php

spl_autoload_register(function ($class_name) {
    include "database/" . $class_name . '.php';
});

require_once(__DIR__.'/comments.php');

if (isset($_GET['gameId'])) {
    $gameId = $_GET['gameId'];
    
    $db = new DB();
    $connection = $db->connect_to_db();
    
    if (isset($_POST['comment'])) {
    
        $comment = $_POST['comment'];
        $stmt = $connection->prepare("INSERT INTO comment (user_id, game_id, content) VALUES (?, ?, ?)");
        
        $userId = 1;
        //TODO : SKIFT USER ID TIL SESSION ID
        $stmt->bind_param("iis", $userId, $gameId, $comment);
        $stmt->execute();
    }

    $game_query = "SELECT * 
                    FROM game 
                    WHERE game.id = $gameId";
    $game_result = $connection->query($game_query);

    $comment_query = "SELECT COMMENT.id AS comment_id, user.id, user.nickname AS user_nickname, 
                        COMMENT.user_id, game_id, COMMENT.content AS comment_content
                        FROM COMMENT 
                        JOIN user ON user.id = COMMENT.user_id 
                        WHERE game_id = $gameId";

    $comment_result = $connection->query($comment_query);
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
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="margin-bottom: 20px;">
        <a class="navbar-brand" href="home.php">Game Forum</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">Home<span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <h3><?= $game['title']; ?></h3>
        <b>Release Year</b>
        <p><?= $game['year']; ?></p>
        <b>Genre</b>
        <p><?= $game['genre']; ?></p>
        <b>Description</b>
        <p><?= $game['description']; ?></p>
        <?php 
            create_comments($comment_result);
        ?>
        <div class="form-group">
                <label for="exampleFormControlTextarea1">Write Comment</label>
            <form method="post">
                <textarea class="form-control" name="comment" id="exampleFormControlTextarea1" rows="3"></textarea>
                <input type="hidden" name="gameId" value=" <?php echo $_GET['gameId']; ?>"/>
                <input class="btn btn-dark" type="submit"  role="button" value="Post">
            </form>
        </div>
    </div>
</body>

</html>