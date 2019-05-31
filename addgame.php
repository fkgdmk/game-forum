<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
include "partials/navbar.php";
include "actions/addgame.actions.php";


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
    <?php create_navbar() ?>
    <div class="container">
        <h3>Add New Game</h3>
        <form method="post" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                <div class="custom-file">
                    <input type="file" class="custom-file-input" name="file" id="file" id="validatedCustomFile">
                    <label class="custom-file-label" for="validatedCustomFile">Choose file...</label>
                    <div class="invalid-feedback">Example invalid custom file feedback</div>
                </div>
            </div>
            <input type="submit" name="submit" class="btn btn-primary">
            <?php echo $message; ?>
        </form>
    </div>
</body>

</html>