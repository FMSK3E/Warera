<?php

class UserModel extends Model {

    public static function signup() {
        // Pour s'assure que l'user soit sur la page de manière normale
        if (isset($_POST['signup-submit'])) {

            // On prend les params donnés par l'user
            $username = $_POST['uid'];
            $email = $_POST['email'];
            $password = $_POST['pwd'];
            $passwordRepeat = $_POST['repwd'];

            // Si des paramètres sont vides, on renvoie une erreur
            if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
                header("Location: index.php?action=signup-form&error=emptyfields&uid=".$username."&email=".$email);
                exit();
            // Si l'email et l'username ne sont pas bons, on renvoie une erreur
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
                header("Location: index.php?action=signup-form&error=invalidemailuid");
                exit();
            // Si l'email n'est pas bon, on renvoie une erreur
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                header("Location: index.php?action=signup-form&error=invalidemail&uid=".$username);
                exit();
            // Si l'username n'est pas bon, on renvoie une erreur
            } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
                header("Location: index.php?action=signup-form&error=invaliduid&email=".$email);
                exit();
            // Si le mdp et la répétition de mdp ne correspondent pas, on renvoie une erreur
            } else if ($password != $passwordRepeat) {
                header("Location: index.php?action=signup-form&error=password&uid=".$username."&email=".$email);
                exit();
            // Si on a rencontré aucun problème, on regarde si un user avec ce nom ou un email n'existent pas déjà
            } else {
                $stmt = Model::connect()->prepare("SELECT * FROM users WHERE username=? OR email=?;");
                $stmt->execute([$username, $email]);

                $datas = array();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $datas[] = $row;
                }

                if ($datas != null) {
                    header("Location: index.php?action=signup-form&error=usernameoremailtaken");
                    exit();
                // Si tout est bon, on peut créer l'user
                } else {
                    $userRole = 100;
                    $userLevel = 1;
                    $userStrength = 1;
                    $userEcoSkill = 1;
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    // Dans la DB users
                    $stmt = Model::connect()->prepare("INSERT INTO users (role, email, username, password, level, strength, eco_skill) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$userRole, $email, $username, $hashedPassword, $userLevel, $userStrength, $userEcoSkill]);

                    // Dans la DB users_inventory
                    $stmt = Model::connect()->query("INSERT INTO users_inventory (iron, cereals, weapons, food) VALUES (1000, 1000, 100, 100)");

                    // Dans la DB users_gallery
                    $stmt = Model::connect()->query("SELECT * FROM users WHERE username='$username'");

                    $datas = array();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $datas[] = $row;
                    }

                    if ($datas == null) {
                        header("Location: index.php?action=signup-form&error=cantfinalize");
                        exit();
                    } else {
                        $userid = $datas[0]['id'];
                        $stmt = Model::connect()->query("INSERT INTO users_gallery (id_user, status) VALUES ('$userid', 0)");
                    }
                }
            }
        }
    }

    public static function login() {

        // On prend les infos que l'user a donnée
        $mailuid = $_POST['mailuid'];
        $password = $_POST['pwd'];

        if (empty($mailuid) || empty($password)) {
            header("Location: index.php?error=emptyfields&mailuid=".$mailuid);
            exit();
        // Si les params sont remplis, on cherche les infos de la personne correspondant au mail / à l'id
        } else {
            $stmt = Model::connect()->prepare("SELECT * FROM users WHERE username=? OR email=?;");
            $stmt->execute([$mailuid, $mailuid]);

            $datas = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $datas[] = $row;
            }

            if ($datas == null) {
                header("Location: index.php?error=usernotfound");
                exit();
            // Si on trouve des résultats, on check le password
            } else {
                $pwdCheck = password_verify($password, $datas['0']['password']);
                // Si l'user s'est trompé de mdp, on renvoie une erreur
                if ($pwdCheck == false) {
                    header("Location: index.php?error=invalidpassword&mailuid=".$mailuid);
                    exit();
                // Si le mdp est correct, on lance la session et on donne comme id/username les params trouvés dans la db
                } else {
                    $_SESSION['userId'] = $datas['0']['id'];
                    $_SESSION['userUid'] = $datas['0']['username'];
                    $_SESSION['userEmail'] = $datas['0']['email'];
                    $_SESSION['role'] = $datas['0']['role'];

                    $id = $_SESSION['userId'];
                    // Recherche sql dans la DB pour savoir si l'user a une photo de profil
                    $stmt = Model::connect()->query("SELECT * FROM users_gallery WHERE id_user='$id'");
                    // On fait le while de recherche
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        // Si on voit que l'user à un id similaire dans la table users_gallery, on insère son image dans $_SESSION
                        if ($row['status'] == 1) {
                            $fileName = "public/img/profile/profile".$id."*";
                            $fileInfo = glob($fileName);
                            $fileExt = explode('.', $fileInfo[0]);
                            $fileActualExt = $fileExt[1];
                            $_SESSION['picture'] = "public/img/profile/profile".$id.".".$fileActualExt;

                        // Sinon on upload une img par défaut
                        } else {
                            $_SESSION['picture'] = "public/img/profile/profiledefault.jpg";
                        }
                    }
                }
            }
        }
    }

    public static function logout() {

        // On ferme simplement la session en cours
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php");
    }

    public static function profile() {

        // Pour des raisons pratiques, on crée une variable $id
        $id = $_SESSION['userId'];
        // Recherche sql dans la DB pour savoir si userid correspond à l'id de l'user actuel
        $stmt = Model::connect()->query("SELECT * FROM users_gallery WHERE id_user='$id'");

        require 'views/profile.php';
    }

    public static function uploadPicture() {

        $id = $_SESSION['userId'];

        if(isset($_POST['uploadimg-submit'])) {
            $file = $_FILES['uploaded-file'];

            //$fileName = $_FILES['uploaded-files']['name'];
            // OU
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];
            // Il y a name - tmp_name - size - error - type

            // On explose ce qu'il y a avant le "." (en comptant le "."), il ne reste que l'extension
            $fileExt = explode('.', $fileName);
            // On met tous les caractères en minuscule, pour raison pratique
            $fileActualExt = strtolower(end($fileExt));
            // On crée un tableau d'extention autorisées
            $allowed = array('jpg', 'jpeg', 'png', 'pdf');

            // On check si l'extension est autorisée
            if (!in_array($fileActualExt, $allowed)) {
                header("Location: index.php?action=profile&error=badtype");
                exit();
            } else {
                // On check s'il y a eu une erreur
                if ($fileError === 0) {
                    // On check que la taille ne soit pas trop grande
                    if ($fileSize > 1000000) {
                        header("Location: index.php?action=profile&error=toobig");
                        exit();
                    } else {
                        // On peut créer un id aléatoire "XXXXXX.XXXX.extensionfichier"
                        //$fileNameNew = uniqid('', true).".".$fileActualExt;
                        // Ou on peut créer un id perso pour les utilisateurs
                        $fileNameNew = "profile".$id.".".$fileActualExt;
                        // On choisit la destination avec le nouveau nom
                        $fileDest = 'public/img/profile/'.$fileNameNew;
                        // On supprime tous les fichiers avec un id similaire (utile s'il ya des extensions différentes)
                        foreach(glob("public/img/profile/profile".$id.".*") as $match) {
                            unlink($match);
                        }
                        // On bouge le fichier vers sa destination (on rappelle que le fichier a encore un temp name)
                        move_uploaded_file($fileTmpName, $fileDest);
                        // On modifie l'image de profile sur l'utisateur log
                        $stmt = Model::connect()->query("UPDATE users_gallery SET status=1 WHERE id_user='$id';");
                        $_SESSION['picture'] = $fileDest;
                        header("Location: index.php?action=profile&upload=success");
                        exit();
                    }
                } else {
                    header("Location: index.php?action=profile&error=unknown");
                    exit();
                }
            }
        }
    }

    public static function deletePicture() {

        $id = $_SESSION['userId'];

        // On indique le nom du fichier
        $fileName = "public/img/profile/profile".$id.".*";
        // Si on cherche des fichiers avec un id de 1, alors l'image des id 10/11/etc seront aussi prises
        $fileInfo = glob($fileName);
        // Pour contrer ça on met [0] après $fileInfo pour n'avoir qu'un résultat (le bon)
        $fileExt = explode('.', $fileInfo[0]);
        // L'extension est ici stockée dans le 3, on la récupère
        $fileActualExt = $fileExt[1];
        // On a normalement le nom du fichier entier
        $file = "public/img/profile/profile".$id.".".$fileActualExt;

        // On supprime le fichier existant
        if (!unlink($file)) {
            header("Location: index.php?action=profile&error");
            exit();
        }
        // On indique que l'utilisateur n'a plus de photo de profil perso
        $stmt = Model::connect()->query("UPDATE users_gallery SET status=0 WHERE id_user='$id';");
        $_SESSION['picture'] = "public/img/profile/profiledefault.jpg";

        header("Location: index.php?action=profile&delete=success");
    }
}