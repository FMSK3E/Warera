<?php

ob_start();

// Si on est connecté le profil peut s'afficher, sinon on renvoie à l'index
if (isset($_SESSION['userId'])) { ?>
    <!-- On affiche des infos ainsi qu'un formulaire pour changer d'image de profil -->
    <div class="profile-contender">
        <?php echo "<img src=".$userInfos['picture']." class='profile-img'>"; ?>
        <br>
        <?php
        // Si on est bien sur notre compte, alors on peut modifier des trucs
        if ($_SESSION['userId'] == $userInfos['id']) {
            if (isset($_GET['profile_action'])) {
                if ($_GET['profile_action'] == "avatar") {
                    echo '<form action="index.php?action=upload_picture" method="POST" enctype="multipart/form-data">
                        <input type="file" name="uploaded_file">
                        <button type="submit" name="uploadimg_submit">Upload picture</button>
                    </form>
                    <form action="index.php?action=delete_picture" method="POST">
                        <button type="submit" name="deleteimg_submit">Delete picture</button>
                    </form><br>';
                }
            } else {
                echo '<a style="margin-left: 175px" href="index.php?action=profile&id='.$_SESSION['userId'].'&profile_action=avatar">Change profile picture</a><br>';
            }
        }

    echo 'ID : '.$userInfos['id'].'<br>
    Username : '.$userInfos['username'].'<br>'.
    $userInfos['email'].'<br>'.
    $userInfos['role'].'</div>';
} else {
    //header("Location: index.php");
    exit();
}

$content = ob_get_clean();

require 'base.php';