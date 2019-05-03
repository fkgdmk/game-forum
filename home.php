<?php

spl_autoload_register(function ($class_name) {
    include "controller/database/" . $class_name . '.php';
});

$db = new DB();
$connection = $db->connectToDb();
$sql = "SELECT * FROM game";
$result = $connection->query($sql);

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
    <link rel="stylesheet" href="view/style/home.css">
    <title>Home</title>
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
                    <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container">
        <div class="btn-container">
            <a class="btn btn-dark" href="view/html/addgame.php?varname=<?php echo $test ?>" role="button">Add Game</a>
        </div>
        <?php
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '<a href="view/html/game.php?gameId=' . $row['id'] . '">' .
                    '<div class="game-card" style="width: 18rem;">' .
                    '<img src="https://images.g2a.com/newlayout/600x351/1x1x0/07ed1041ce55/5b5af938ae653a6139610943" alt="Card image cap">' .
                    '<div class="card-content">' .
                    '<h5 class="card-title">' . $row['title'] . ' (' . $row['year'] . ')</h5>' .
                    '<b>Genre: ' . $row['genre'] . '</b>' .
                    '<p class="card-text">' . $row['description'] . '</p>' .
                    '</div>' .
                    '</div>' .
                    '</a>';
            }
            echo "</table>";
        } else {
            echo "<p>The games list is empty</p>";
        }
        ?>
    </div>
</body>

</html>