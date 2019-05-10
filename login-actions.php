<?php
include "database/DB.php";

function verify_user($email, $password)
{
    $db = new DB();
    $connection = $db->connect_to_db();

    $stmt = $connection->prepare("SELECT email, password 
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
                return true;
            }
            return false;
        }
    } 
    return false;
}
