<?php

ob_start();

// Si on est connecté le profil peut s'afficher, sinon on renvoie à l'index
if (isset($_SESSION['userId'])) { ?>
    <!-- On affiche des infos ainsi qu'un formulaire pour changer d'image de profil -->
    <div class="profile-contender">
        <?php echo "<img src=".$_SESSION['picture']." class='profile-img'>"; ?>
        <br>
        <?php if (isset($_GET['profile-action'])) {
            if ($_GET['profile-action'] == "avatar") { ?>
                <form action='index.php?action=upload_picture' method='POST' enctype='multipart/form-data'>
                    <input type='file' name='uploaded_file'>
                    <button type='submit' name='uploadimg_submit'>Upload picture</button>
                </form>
                <form action='index.php?action=delete_picture' method='POST'>
                    <button type='submit' name='deleteimg_submit'>Delete picture</button>
                </form><br>
        <?php }
        } else {
            echo '<a style="margin-left: 175px" href="index.php?action=profile&profile-action=avatar">Change profile picture</a><br>';
        }

    echo "ID : ".$_SESSION['userId']."<br>
    Username : ".$_SESSION['userUid']."<br>".
    $_SESSION['userEmail']."<br>".
    $_SESSION['role']."</div>";
} else {
    header("Location: index.php");
}

$content = ob_get_clean();

require 'base.php';