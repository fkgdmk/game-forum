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
    <link rel="stylesheet" href="style/sign-up.css">
    <title>Sign up</title>
</head>

<?php 
    include "database/DB.php";

    $email_error = "";
    $action = "sign_up.php";

    //Check if this user already exist
    function user_exist(){
        $db = new DB();
        $conn = $db->connect_to_db();
        $statement = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $statement->bind_param("s", $_POST["email"]);
        $statement->execute();

        $result = $statement->get_result();

        if ($result->num_rows === 0){
            //This user doesn't exist
            $statement->close();
            $conn->close();
            return false;
        }

        else{
            //This user already exist
            $statement->close();
            $conn->close();
            return true;
        }
    }

    //Insert a new user
    function insert_new_user($email, $nickname, $password){
        $db = new DB();
        $conn = $db->connect_to_db();
        $statement = $conn->prepare("INSERT INTO user (email, nickname, password) VALUES(?, ?, ?)");

        $hashedPassword = hash_password($password);

        $statement->bind_param("sss", $email, $nickname, $hashedPassword);
        $statement->execute();
        $statement->close();
        $conn->close();
    }

    //Hash the password with BCRYPT
    function hash_password($password){
        $options = [
                'cost' => 10,
        ];
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

    //Run this only when method is post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (user_exist()) {
            $action = "sign_up.php";
            $email_error = "This email already exist";
        }
        else {
            //Check if these POST variables exist
            if (isset($_POST["email"]) && isset($_POST["nickname"]) && isset($_POST["password"])) {

                insert_new_user($_POST["email"], $_POST["nickname"], $_POST["password"]);
                $action = "home.php";
                header("Location: home.php");

        }
    }
}

?>

<body>
    <div class="container">
        <h3 class="title">Create a new user here</h3>
        <form method="POST" action="sign_up.php">
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" class="form-control" placeholder= "example@hotmail.com"
                       name="email"> <?php //$email_error ?>
            </div>
            <div class="form-group">
                <label>Nickname(For public use)</label>
                <input type="text" class="form-control" placeholder= "johnDoe246" name="nickname">
            </div>
            <div class="form-group">
                <label>password</label>
                <input type="password" class="form-control" placeholder= "********" name="password">
            </div>
            <div>
                <input type="submit" class="btn btn-primary" value="Sign up">
            </div>
        </form>
    </div>
</body>


</html>

    
