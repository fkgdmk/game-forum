
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
    include "database/DB.php";
    include "partials/navbar.php";
    $title_error = "";
    $succes_msg = "";

    //Check if game already exist
    function game_exist(){
        $db = new DB();
        $conn = $db->connect_to_db();
        $statement = $conn->prepare("SELECT title 
                                    FROM game 
                                    WHERE title = ? 
                                    AND year = ?");
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
    function add_game($title, $release_year, $genre, $description, $user_id, $file_name, $file_destination){


        $db = new DB();
        $conn = $db->connect_to_db();
        $sanitized_title = mysqli_real_escape_string($conn, $title);
        $sanitized_release_year = mysqli_real_escape_string($conn, $release_year);
        $sanitized_genre = mysqli_real_escape_string($conn, $genre);
        $sanitized_description = mysqli_real_escape_string($conn, $description);
        $sanitized_user_id = mysqli_real_escape_string($conn, $user_id);

        
        $null = NULL;
        
        $statement = $conn->prepare("INSERT INTO game (title, year, genre, description, user_id, image_name, image_folder) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $statement->bind_param("sississ", $sanitized_title, $sanitized_release_year, $sanitized_genre, $sanitized_description, $sanitized_user_id, $file_name, $file_destination);
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
            if (isset($_POST['submit'])) {
                $file = $_FILES['file'];
                $file_name = $_FILES['file']['name'];
                $file_tmp_name = $_FILES['file']['tmp_name'];
                $file_size = $_FILES['file']['size'];
                $file_error = $_FILES['file']['error'];
                $file_type = $_FILES['file']['type'];

                $file_ext = explode('.', $file_name);
                $file_actual_ext = strtolower(end($file_ext));

                $allowed = array('jpg', 'jpeg', 'png');

                if (in_array($file_actual_ext, $allowed)) {
                    if ($file_error === 0) {
                        if ($file_size < 500000) {
                            $file_new_name = uniqid('', true).".".$file_actual_ext;
                            $file_destination = 'images/'.$file_new_name;
                            move_uploaded_file($file_tmp_name, $file_destination);
                            echo 'succes';
                        } else {
                            echo 'Size Error';
                        }
                    } else {
                        echo 'File Error';
                    }
                } else {
                    echo 'Type Error';
                }

                if (isset($img)) {
                    echo $img;
                } else {
                    echo 'wtf';
                }

                // TODO add the correct user_id when login and session system has been made
                add_game($_POST["title"], $_POST["year"], $_POST["genre"], $_POST["description"], $_SESSION['user_id'], $file_name, $file_destination);
                $succes_msg = "Succes! The game has been added to the list";
            }
        }
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
    <link rel="stylesheet" href="game.css">
    <title>Game</title>
</head>

<body>
    <?php create_navbar()?>
    <div class="container">
        <h3>Add New Game</h3>
        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
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
                <input type="number" class="form-control" name="year">
            </div>
            <div class="form-group">
                <label>Image: </label>
                <input type="file" name="file" id="file">
            </div>
            <input type="submit" name="submit" class="btn btn-primary"> 
            <?php echo $succes_msg; ?>
        </form>
    </div>
</body>

</html>