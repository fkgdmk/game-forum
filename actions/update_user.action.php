<?php 

include "./database/DB.php";


    function get_user(){
        $db = new DB();
        $conn = $db->connect_to_db();
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT user.email AS email, user.nickname as nickname FROM user
                WHERE user.id = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param("i", $user_id);
        $statement->execute();
        $result = $statement->get_result();
        
        $user = $result->fetch_assoc();
        $conn->close();
        return $user;
        
    }

    function update_user($email, $nickname){
        $user_id = $_SESSION['user_id'];
        $db = new DB();
        $conn = $db->connect_to_db();
        if($conn->connect_errno){
            printf("Connect failed: %s\n", $conn->connect_error);
        }
        $statement = $conn->prepare("UPDATE user SET user.email = ?, user.nickname = ? WHERE user.id = ?;");
        $statement->bind_param("ssi", $email, $nickname, $user_id);
        $statement->execute();
        $statement->close();
        $conn->close();

        return true;
    }

    function update_password($password){
        $user_id = $_SESSION['user_id'];
        $password = password_hash(PASSWORD_BCRYPT, $password);
        $db = new DB();
        $conn = $db->connect_to_db();
        if($conn->connect_errno){
            printf("Connect failed: %s\n", $conn->connect_error);
        }
        $statement = $conn->prepare("UPDATE user SET user.password = ? WHERE user.id = ?;");
        $statement->bind_param("si", $password, $user_id);
        $statement->execute();
        $statement->close();
        $conn->close();
    }


