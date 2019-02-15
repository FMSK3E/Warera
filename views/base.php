<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
    <script src="js/jquery-3.3.1.min.js"></script>

    <title>Page Title</title>
</head>
<body style="background-color: rgb(226, 235, 234);">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">
                <img src="public/img/1.jpg" alt="Logo" style="width:40px;">
            </a>

            <!-- Bouton pour rendre responsive la navbar -->
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navb">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Links -->
            <div class="collapse navbar-collapse" id="navb">
                <ul class="navbar-nav mr-auto">
                    <!-- Profile -->
                    <?php
                    if (isset($_SESSION['userId'])) {
                    echo '<li class="nav-item">
                        <a class="nav-link" href=index.php?action=profile&id='.$_SESSION['userId'].'>Profile</a>
                    </li>'; } ?>
                    <!-- Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            zzz
                        </a>
                        <div class="dropdown-menu bg-dark">
                            <a class="dropdown-item bg-dark text-white" href="#">zbeb 1</a>
                            <a class="dropdown-item bg-dark text-white" href="#">Link 2</a>
                            <a class="dropdown-item bg-dark text-white" href="#">Link 3</a>
                        </div>
                    </li>
                    <!-- Market -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            Market
                        </a>
                        <div class="dropdown-menu bg-dark">
                            <a class="dropdown-item bg-dark text-white" href="#">Job Market</a>
                            <a class="dropdown-item bg-dark text-white" href="index.php?action=product_market">Product Market</a>
                            <a class="dropdown-item bg-dark text-white" href="#">Monetary Market</a>
                        </div>
                    </li>
                </ul>
                <!-- Formulaires de signup - login/logout -->
                <?php
                // Si on est connecté, on affiche le logout et l'image de profil
                if (isset($_SESSION['userId'])) :
                    echo '<img src='.$_SESSION['picture'].' style="width:40px; height: 40px; margin-right: 20px;">'; ?>
                    <form class="form-inline my-2 my-lg-0" action="index.php?action=logout" method="POST">
                        <button class="btn btn-success my-2 my-sm-0" type="submit" name="logout_submit">Logout</button>
                    </form>
                <?php else : ?>
                    <!-- Sinon on affiche le signup - login -->
                    <form class="form-inline my-2 my-lg-0" action="index.php?action=login" method="POST">
                        <?php
                        if (isset($_GET['mailuid'])) {
                            $mailuid = $_GET['mailuid'];
                            echo '<input class="form-control mr-sm-2" type="text" name="mailuid" value="'.$mailuid.'">';
                        } else {
                            echo '<input class="form-control mr-sm-2" type="text" name="mailuid" placeholder="Username/Email">';
                        }
                        ?>
                        <input class="form-control mr-sm-2" type="password" name="pwd" placeholder="Password">
                        <button  class="btn btn-success my-2 my-sm-0" type="submit" name="login_submit">Login</button>
                    </form>
                    <form class="form-inline ml-3 my-lg-0" action="index.php?action=signup_form" method="POST">
                        <button class="btn btn-success my-2 my-sm-0" type="submit" name="signup_submit">Signup</button>
                    </form>
                <?php endif ?>
            </div>
        </nav>
    </header>
    <?= $content ?>
</body>
</html>


<!--

    if (isset($_SESSION['userId'])) {
                echo '<form method="post" action="index.php?action=logout" class="form-inline my-2 my-lg-0">
                        <button class="btn btn-success my-2 my-sm-0" type="submit">Logout</button>
                    </form>';
                    } else {
                echo '<form method="post" action="index.php?action=login" class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="text" name="mailuid" placeholder="Username">
                        <input class="form-control mr-sm-2" type="password" name="pwd" placeholder="Password">
                        <button  class="btn btn-success my-2 my-sm-0" type="submit">Login</button>
                    </form>
                <form method="post" action="index.php?action=signup" class="form-inline ml-3 my-lg-0">
                        <button class="btn btn-success my-2 my-sm-0" type="submit">Signup</button>
                    </form>';
            }