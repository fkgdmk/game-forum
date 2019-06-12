<?php
include "./database/DB.php";
include "./credentials.php";

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
                if(isset($_POST['token'])){
                    $tkn = hash("sha256", $_POST['token']);
                }
                if($tkn == $_SESSION['token'])
                {
                    if ($user['admin']) {
                        $_SESSION['isAdmin'] = true;
                    }
                    else{
                        $_SESSION['isAdmin'] = false;
                    }
                    return $user['id'];
                }
                else{
                    return -1;
                }
            } 
            else {
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
    echo $email;

    $auth_code = rand(10000, 99999);
    $mail = new \SendGrid\Mail\Mail();
    $mail->setFrom("fredrik0301@gmail.com", "Game Forum");
    $mail->setSubject("Authentication code");
    $mail->addTo($email, "Example user");
    $mail->addContent("text/plain", "$auth_code");
    $mail->addContent(
        "text/html",
        "<h1>$auth_code</h1>"
    );
    $sendgrid = new \SendGrid($API_KEY);
    try {
        $response = $sendgrid->send($mail);
        print $response->statusCode() . "\n";
        print_r($response->headers());
        print $response->body() . "\n";
        return $auth_code;
    } catch (Exception $e) {
        echo 'Caught exception: ' . $e->getMessage() . "\n";
    }
}

function create_random_token(){
    $rand = substr(md5(microtime()),rand(0,26),20);
    return $rand;
}

$user_id = -1;
if (isset($_POST['email']) && isset($_POST['password'])) {

    //Using recaptcha to check if user is not a robot
    $secret = $recaptcha_secret;
    $response_key = $_POST['g-recaptcha-response'];
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response_key&remoteip=$user_ip";
    $response = file_get_contents($url);
    $response = json_decode($response);

    if ($response->success) {
        $email = rtrim($_POST['email']);
        $password = $_POST['password'];
        $three_attempts = check_failed_attempted_logins($email);

        if (!$three_attempts) {
            $user_id = verify_user($email, $password);

            if ($user_id > 0) {
                $auth_code = send_2step_code($email);

                $_SESSION['authCode'] = $auth_code;
                header('Location: 2step_auth.php?userId=' . $user_id);
            }
        }

        $user_authenticated = $user_id > 0 ? 1 : 0;
        create_login_log($email, $user_authenticated);
    }
}


