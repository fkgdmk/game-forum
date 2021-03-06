<?php
session_start();
include "actions/login.actions.php";
include "credentials.php";
$tkn = create_random_token();
$_SESSION['token'] = hash("sha256", $tkn);


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
    <script src="https://www.google.com/recaptcha/api.js?render=reCAPTCHA_site_key"></script>
    <link rel="stylesheet" href="style/login.css">
    <title>Login page</title>
</head>

<body>
    <div class="container">
        <h3 class="title">Login</h3>
        <?php if (isset($user_id) && $user_id < 0) : ?>
            <form method="POST">
                <input type="hidden" value="<?php echo $tkn; ?>" name="token">
                <div class="form-group">
                    <label>E-mail</label>
                    <input class="form-control" placeholder="example@hotmail.com" name="email">
                </div>
                <div class="form-group">
                    <label>password</label>
                    <input type="password" class="form-control" placeholder="********" name="password">
                </div>
                <?php if (isset($user_authenticated) && !$user_authenticated) : ?>
                    <div class="login-denied" style="color: red">
                        <p>Wrong username or password</p>
                    </div>
                <?php endif ?>
                <?php if (isset($three_attempts) && $three_attempts) : ?>
                    <div class="login-denied" style="color: red">
                        <p>Try again in 5 minutes</p>
                    </div>
                <?php endif ?>
                <div class="g-recaptcha" data-sitekey="<?=$recaptcha_site?>"></div>
                <br />
                <div>
                    <input type="submit" class="btn btn-primary" value="Login">
                </div>
            </form>
        <?php endif ?>
        <a href="forgot_pass.php" class="forgot-password">Forgot password?</a>
        <br>
        <a href="sign_up.php" class="sign-up">Sign up if you don't have a account</p>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>


</html>