<?php

spl_autoload_register(function ($class_name) {
    include "database/" . $class_name . '.php';
});

if (isset($_GET['gameId'])) {
    $gameId = $_GET['gameId'];
    
    $db = new DB();
    $connection = $db->connect_to_db();
    $sql = "SELECT * FROM game WHERE game.id = $gameId";
    $result = $connection->query($sql);

    $game = mysqli_fetch_assoc($result);
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
        <h3><?php echo $game['title']; ?></h3>
        <b>Release Year</b>
        <p><?php echo $game['year']; ?></p>
        <b>Genre</b>
        <p><?php echo $game['genre']; ?></p>
        <b>Description</b>
        <p><?php echo $game['description']; ?></p>
        <div class="comments-container">
            <h5>Comments</h5>
            <div class="comments">
                <hr>
                <h6>Bruger - 10.34</h6>
                <p>Det er et ok spil men også en smule lort</p>
                <hr>
            </div>
            <form>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Write Comment</label>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>  
            </form>
        </div>
    </div>
</body>

</html>