<?php

if (isset($_POST['logout'])) {
    session_destroy();
    header( 'Location: index.php' );
}

function create_navbar()
{
    ?>
    <nav class="navbar navbar-default navbar-expand-lg navbar-light bg-light" style="margin-bottom: 20px;">
        <a class="navbar-brand" href="home.php">Game Forum</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="nav navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                    <a class="nav-link" href="update_user.php">Profile <span class="sr-only">(current)</span></a>
                    <?php if($_SESSION['isAdmin'] == 1) 
                    { ?>
                    <a class="nav-link" href="delete.php">Delete users <span class="sr-only">(current)</span></a>
                    <?php } ?>
                </li>
            </ul>
        </div>
        <form method="POST" action=""> 
            <input class="btn btn-outline-secondary my-2 my-sm-0" type="submit" name="logout" value="Log out">
        </form> 
    </nav>
<?php
}