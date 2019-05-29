<?php
include "database/DB.php";
include "credentials.php";

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
                // $_SESSION['email'] = $user['email'];
                if ($user['admin']) {
                    $_SESSION['isAdmin'] = true;
                }
                else{
                    $_SESSION['isAdmin'] = false;
                }
                return $user['id'];
            } else {
                return -1;
            }
        }
    }
    return -1;
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

function send_2step_code(string $email)
{
    require './sendgrid/vendor/autoload.php';
    require './credentials.php';
    echo '<br>'.gettype($email);

    $auth_code = rand(10000, 99999);
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("fredrik0301@gmail.com", "Game Forum");
    $email->setSubject("Authentication code");
    $email->addTo("fredrik0301@gmail.com", "Example User");
    $email->addTo("hamzah1996@hotmail.com", "Example User");
    $email->addContent("text/plain", "$auth_code");
    $email->addContent(
        "text/html",
        "<h1>$auth_code</h1>"
    );
    $sendgrid = new \SendGrid($API_KEY);
    try {
        $response = $sendgrid->send($email);
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";
        return $auth_code;
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";
    }
}


