<?php
include "database/DB.php";

function verify_user($email, $password)
{
    $db = new DB();
    $connection = $db->connect_to_db();
    $stmt = $connection->prepare("SELECT email, id, admin, password 
                                FROM user 
                                WHERE email = ?");

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $users = $stmt->get_result();
    $stmt->close();
    $connection->close();

    if ($users->num_rows > 0) {
        while ($user = $users->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_id'] = $user['id'];

                if ($user['admin']) {
                    $_SESSION['isAdmin'] = true;
                }
                return true;
            } else {
                return false;
            }
        }
    }
    return false;
}

function create_login_log($email, $authenticated)
{
    $date = date("Y-m-d H:i:s", time());

    $db = new DB();
    $connection = $db->connect_to_db();
    $stmt = $connection->prepare("INSERT into login_log 
                                (email, authenticated, datetime) 
                                values (?, ?, ?)");

    $stmt->bind_param("sis", $email, $authenticated, $date);
    $stmt->execute();
    $stmt->close();
    $connection->close();
}

function check_failed_attempted_logins($email)
{
    $db = new DB();
    $connection = $db->connect_to_db();
    $stmt = $connection->prepare("SELECT * 
                                FROM login_log 
                                WHERE TIMESTAMPDIFF(MINUTE, login_log.datetime, NOW()) <= 5 
                                AND login_log.email = ?
                                AND login_log.authenticated = 0");

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $attempted_logins = $stmt->get_result();
    $stmt->close();
    $connection->close();

    echo $attempted_logins->num_rows;
    
    if ($attempted_logins->num_rows >= 3) {
        return true;
    }
    return false;
}
