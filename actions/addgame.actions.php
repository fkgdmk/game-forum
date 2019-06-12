<?php
include "./database/DB.php";

function game_exist()
{
    $db = new DB();
    $conn = $db->connect_to_db();
    $statement = $conn->prepare("SELECT title 
                                    FROM game 
                                    WHERE title = ? 
                                    AND year = ?");
    $statement->bind_param("si", $_POST["title"], $_POST["year"]);
    $statement->execute();
    $result = $statement->get_result();

    if ($result->num_rows === 0) {
        //This game doesn't exist
        $statement->close();
        $conn->close();
        return false;
    } else {
        //This game already exist
        $statement->close();
        $conn->close();
        return true;
    }
}

//Add games with prepared statements
function add_game($title, $release_year, $genre, $description, $user_id, $file_name, $file_destination)
{
    $db = new DB();
    $conn = $db->connect_to_db();
    $sanitized_title = mysqli_real_escape_string($conn, $title);
    $sanitized_release_year = mysqli_real_escape_string($conn, $release_year);
    $sanitized_genre = mysqli_real_escape_string($conn, $genre);
    $sanitized_description = mysqli_real_escape_string($conn, $description);
    $sanitized_user_id = mysqli_real_escape_string($conn, $user_id);


    $statement = $conn->prepare("INSERT INTO game (title, year, genre, description, user_id, image_name, image_folder) VALUES(?, ?, ?, ?, ?, ?, ?)");
    $statement->bind_param("sississ", $sanitized_title, $sanitized_release_year, $sanitized_genre, $sanitized_description, $sanitized_user_id, $file_name, $file_destination);
    $statement->execute();
    $statement->close();
    $conn->close();
}

$title_error = "";
$message = "";

//Only run when this page is loaded as a POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (game_exist()) {
        $title_error = "This game already exist create another game";
    } else {
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
                        $file_new_name = uniqid('', true) . "." . $file_actual_ext;
                        $file_destination = 'images/' . $file_new_name;
                        move_uploaded_file($file_tmp_name, $file_destination);
                        
                        add_game($_POST["title"], $_POST["year"], $_POST["genre"], $_POST["description"], $_SESSION['user_id'], $file_name, $file_destination);
                        $message = "Succes! The game has been added to the list";
                    } else {
                        $message = 'File Size Error! The game has not been added to the list';
                    }
                } else {
                    $message = 'File Error! The game has not been added to the list';
                }
            } else {
                $message = 'File Type Error! The game has not been added to the list';
            }
        }
    }
}
