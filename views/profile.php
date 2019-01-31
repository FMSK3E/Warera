<?php

ob_start();

// Si on est connecté le profil peut s'afficher, sinon on renvoie à l'index
if (isset($_SESSION['userId'])) {

    // On affiche des infos ainsi qu'un formulaire pour changer d'image de profil
    echo "<img class='XXXXXXX' src=".$_SESSION['picture'].">";
    echo "ID : ".$id."<br>";
    echo "Username : ".$_SESSION['userUid']."<br>";
    echo $_SESSION['userEmail']."<br>";
    echo $_SESSION['role']."<br>";
    echo "<form action='index.php?action=upload-picture' method='POST' enctype='multipart/form-data'>
            <input type='file' name='uploaded-file'>
        <button type='submit' name='uploadimg-submit'>Upload picture</button>
        </form>";
    echo "<form action='index.php?action=delete-picture' method='POST'>
            <button type='submit' name='deleteimg-submit'>Delete picture</button>
        </form>";
} else {
    header("Location: index.php");
}

$content = ob_get_clean();

require 'base.php';