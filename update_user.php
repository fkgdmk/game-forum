<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
include "partials/navbar.php";
include "actions/update_user.action.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(update_user($_POST['email'], $_POST['nickname'])) $message = "Profile changed";
    if(isset($_POST['password'])){
        update_password($_POST['password']);
    }
}

$user = get_user();

$profile_error = "";


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
    <title>Profile</title>
</head>

<body>
    <?php create_navbar() ?>
    <div class="container">
        <h3>Profile</h3>
        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label>E-mail</label>
                <input class="form-control" name="email" value= <?php echo $user['email']?>> <?php echo $profile_error; ?>
            </div>
            <div class="form-group">
                <label>Nickname</label>
                <input class="form-control" name="nickname" value=<?php echo $user['nickname']?> >
            </div>
            <div class="form-group">
                <label>New password</label>
                <input class="form-control" name="genre">
            </div>
            <input type="submit" name="submit" value="Update profile" class="btn btn-primary">
            <?php echo $message; ?>
        </form>
    </div>
</body>

</html>