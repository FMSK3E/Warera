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
                // On affiche d'abord les liens pour changer
                if ($_GET['profile_action'] != "avatar") {
                    echo '<a style="margin-left: 175px" href="index.php?action=profile&id='.$_SESSION['userId'].'&profile_action=avatar">Change profile picture</a><br>';
                }
                if ($_GET['profile_action'] != "username") {
                    echo '<a style="margin-left: 175px" href="index.php?action=profile&id='.$_SESSION['userId'].'&profile_action=username">Change username</a><br>';
                }
                if ($_GET['profile_action'] != "password") {
                    echo '<a style="margin-left: 175px" href="index.php?action=profile&id='.$_SESSION['userId'].'&profile_action=password">Change password</a><br>';
                }

                // Formulaire pour l'avatar
                if ($_GET['profile_action'] == "avatar") {
                    echo '<form style="margin-left: 175px;" action="index.php?action=upload_picture" method="POST" enctype="multipart/form-data">
                        <input style="margin-bottom: 2px; margin-top: 2px;" type="file" name="uploaded_file">
                        <button style="width: 134px; margin-bottom: 2px; margin-top: 2px;" type="submit" name="uploadimg_submit">Upload picture</button>
                    </form>
                    <form style="margin-left: 175px;" action="index.php?action=delete_picture" method="POST">
                        <button style="width: 134px; margin-bottom: 2px; margin-top: 2px;" type="submit" name="deleteimg_submit">Delete picture</button>
                    </form><br>';
                // Formulaire pour le nom d'utilisateur
                } else if ($_GET['profile_action'] == "username") {
                    echo '<form style="margin-left: 175px;" action="index.php?action=change_username" method="POST">
                        <input style="width: 180px; margin-bottom: 2px; margin-top: 2px;" type="text" name="new_username" placeholder="Your new username">
                        <input style="width: 180px; margin-bottom: 2px; margin-top: 2px;" type="password" name="password" placeholder="Confirm with password">
                        <button style="margin-bottom: 2px; margin-top: 2px;" type="submit" name="changeusername_submit">Change your username</button>
                    </form>';
                // Formulaire pour le mot de passe
                } else if ($_GET['profile_action'] == "password") {
                    echo '<form style="margin-left: 175px;" action="index.php?action=change_password" method="POST">
                        <input style="margin-bottom: 2px; margin-top: 2px;" type="password" name="new_password" placeholder="Your new password">
                        <input style="margin-bottom: 2px; margin-top: 2px;" type="password" name="new_repassword" placeholder="Repeat your password">
                        <input style="margin-bottom: 2px; margin-top: 2px;" type="password" name="password" placeholder="Your old password">
                        <button style="margin-bottom: 2px; margin-top: 2px;" type="submit" name="changepassword_submit">Change your password</button>
                    </form>';
                }
            // Si on a pas d'actions, on affiche simplement tous les formulaires
            } else {
                echo '<a style="margin-left: 175px" href="index.php?action=profile&id='.$_SESSION['userId'].'&profile_action=avatar">Change profile picture</a><br>';
                echo '<a style="margin-left: 175px" href="index.php?action=profile&id='.$_SESSION['userId'].'&profile_action=username">Change username</a><br>';
                echo '<a style="margin-left: 175px" href="index.php?action=profile&id='.$_SESSION['userId'].'&profile_action=password">Change password</a><br>';
            }
        }

    echo 'ID : '.$userInfos['id'].'<br>
    Username : '.$userInfos['username'].'<br>
    Nationality : '.$userInfos['nationality'].'<br>'.
    $userInfos['email'].'<br>'.
    $userInfos['role'].'<br><br>';
    if ($_SESSION['userId'] == $userInfos['id']) {
        foreach ($_SESSION['inventory'] as $product) {
            if ($product['amount'] > 0)
                echo ucfirst($product['name']).' : '.$product['amount'].'<br>';
        }
    }
    echo '</div>';
} else {
    header("Location: index.php");
    exit();
}

$content = ob_get_clean();

require 'base.php';