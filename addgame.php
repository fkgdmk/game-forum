<!DOCTYPE html>
<html lang="en">

<?php
    include "database/DB.php";

    $title_error = "";
    $succes_msg = "";

    //Check if game already exist
    function game_exist(){
        $db = new DB();
        $conn = $db->connect_to_db();
        $statement = $conn->prepare("SELECT title FROM game WHERE title = ? AND year = ?");
        $statement->bind_param("si", $_POST["title"], $_POST["year"]);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows === 0){
            //This game doesn't exist
            $statement->close();
            $conn->close();
            return false;
        }

        else{
            //This game already exist
            $statement->close();
            $conn->close();
            return true;
        }
    }

    //Add games with prepared statements
    function add_game($title, $release_year, $genre, $description, $user_id){
        $db = new DB();
        $conn = $db->connect_to_db();
        $statement = $conn->prepare("INSERT INTO game (title, year, genre, description, user_id) VALUES(?, ?, ?, ?, ?)");
        $statement->bind_param("sissi", $title, $release_year, $genre, $description, $user_id);
        $statement->execute();
        $statement->close();
        $conn->close();
        
    }

    //Only run when this page is loaded as a POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (game_exist())
        {
            $title_error = "This game already exist create another game";
        }
        else {
            //Check if these POST variables exist
            if (isset($_POST["title"]) && isset($_POST["year"]) && isset($_POST["genre"]) &&
                isset($_POST["description"])) {
                //TODO add the correct user_id when login and session system has been made
                add_game($_POST["title"], $_POST["year"], $_POST["genre"], $_POST["description"], 2);
                $succes_msg = "Succes! The game has been added to the list";
            }
        }
    }

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="game.css">
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
        <h3>Add New Game</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="form-group">
                <label>Title</label>
                <input class="form-control" name="title"> <?php echo $title_error; ?>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input class="form-control" name="description">
            </div>
            <div class="form-group">
                <label>Genre</label>
                <input class="form-control" name="genre">
            </div>
            <div class="form-group">
                <label>Release Year</label>
                <input class="form-control" name="year">
            </div>
            <input type="submit" class="btn btn-primary"> <?php echo $succes_msg; ?>
        </form>
    </div>
</body>

</html>