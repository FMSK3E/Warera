<?php

class UserModel extends Model {

    public static function findUserInfos($id) {
        $stmt = Model::connect()->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);

        $datas = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($datas == null) {
            header("Location: index.php?error=usernotfound");
            exit();
        } else {

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
                    $datas['picture'] = "public/img/profile/profile".$id.".".$fileActualExt;
                // Sinon on upload une img par défaut
                } else {
                    $datas['picture'] = "public/img/profile/profiledefault.jpg";
                }
                return $datas;
            }
        }
    }

    public static function signup($username, $email, $password, $nationality) {

        // Si on a rencontré aucun problème, on regarde si un user avec ce nom ou un email n'existent pas déjà
        $stmt = Model::connect()->prepare("SELECT * FROM users WHERE username=? OR email=?;");
        $stmt->execute([$username, $email]);

        $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($datas != null) {
            header("Location: index.php?action=signup_form&error=usernameoremailtaken&nationality=".$nationality);
            exit();
        // Si tout est bon, on peut créer l'user
        } else {
            $userRole = 100;
            $userLevel = 1;
            $userStrength = 1;
            $userEcoSkill = 1;
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Dans la DB users
            $stmt = Model::connect()->prepare("INSERT INTO users (role, email, username, password, nationality) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$userRole, $email, $username, $hashedPassword, $nationality]);

            // Dans la DB users_inventory
            $stmt = Model::connect()->query("INSERT INTO users_inventory () VALUES ()");

            // Dans la DB users_wallet
            $stmt = Model::connect()->prepare("SELECT currency FROM countries WHERE country=?");
            $stmt->execute([$nationality]);
            $datas = $stmt->fetch(PDO::FETCH_ASSOC);
            // Pour transformer le tableau qui contient la devise en string
            $currency = implode($datas);
            $currency = strtolower($currency);
            var_dump($currency);

            $stmt = Model::connect()->query("INSERT INTO users_wallet (".$currency.") VALUES (50)");

            // Dans la DB users_gallery
            $stmt = Model::connect()->query("SELECT * FROM users WHERE username='$username'");

            $datas = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($datas == null) {
                header("Location: index.php?action=signup_form&error=cantfinalize");
                exit();
            } else {
                $userid = $datas['id'];
                $stmt = Model::connect()->query("INSERT INTO users_gallery (id_user) VALUES ('$userid')");

                header("Location: index.php?signup=success");
                exit();
            }
        }
    }

    public static function login() {

        // On prend les infos que l'user a donnée
        $mailuid = $_POST['mailuid'];
        $password = $_POST['pwd'];
        if(isset($_POST['login_submit'])) {
            if (empty($mailuid) || empty($password)) {
                header("Location: index.php?error=emptyfields&mailuid=".$mailuid);
                exit();
            // Si les params sont remplis, on cherche les infos de la personne correspondant au mail / à l'id
            } else {
                $stmt = Model::connect()->prepare("SELECT * FROM users WHERE username=? OR email=?;");
                $stmt->execute([$mailuid, $mailuid]);

                $datas = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($datas == null) {
                    header("Location: index.php?error=usernotfound");
                    exit();
                // Si on trouve des résultats, on check le password
                } else {
                    $pwdCheck = password_verify($password, $datas['password']);
                    // Si l'user s'est trompé de mdp, on renvoie une erreur
                    if ($pwdCheck == false) {
                        header("Location: index.php?error=invalidpassword&mailuid=".$mailuid);
                        exit();
                    // Si le mdp est correct, on lance la session et on donne à l'utilisateur les params trouvés dans la db
                    } else {
                        $_SESSION['userId'] = $datas['id'];
                        $_SESSION['userUid'] = $datas['username'];
                        $_SESSION['userEmail'] = $datas['email'];
                        $_SESSION['role'] = $datas['role'];
                        $_SESSION['level'] = $datas['level'];
                        $_SESSION['strength'] = $datas['strength'];
                        $_SESSION['eco_skill'] = $datas['eco_skill'];
                        $_SESSION['nationality'] = $datas['nationality'];

                        // Recherche sql dans la DB pour savoir si l'user a une photo de profil
                        $id = $_SESSION['userId'];
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

                        // Recherche sql dans la DB pour récupérer l'inventaire du joueur
                        $stmt = Model::connect()->query("SELECT * FROM users_inventory WHERE id_user='$id'");
                        // On fait le while de recherche
                        $datas = $stmt->fetch(PDO::FETCH_ASSOC);
                        // Si on voit que l'user à un id similaire dans la table users_iventory, on récupères les données
                        if ($datas != null) {
                            // On retire la colonne id_user qu'on avait récupérer avec le reste de l'inventaire
                            unset($datas['id_user']);
                            // On assigne l'inventaire sur un tableau de $_SESSION
                            foreach ($datas as $key => $value) {
                                $_SESSION['inventory'][$key] = array("name" => $key, 'amount' => $value);
                            }
                        } else {
                            header("Location: index.php?bigerror=inventorynotfound");
                            exit();
                        }
                    }
                }
            }
        } else {
            header("Location: index.php");
            exit();
        }
    }

    public static function logout() {

        // On ferme simplement la session en cours
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php");
    }

    public static function uploadPicture() {

        $id = $_SESSION['userId'];

        if(isset($_POST['uploadimg_submit'])) {
            $file = $_FILES['uploaded_file'];

            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];

            // On explose ce qu'il y a avant le "." (en comptant le "."), il ne reste que l'extension
            $fileExt = explode('.', $fileName);
            // On met tous les caractères en minuscule, pour raison pratique
            $fileActualExt = strtolower(end($fileExt));
            // On crée un tableau d'extention autorisées
            $allowed = array('jpg', 'jpeg', 'png', 'pdf');

            // On check si l'extension est autorisée
            if (!in_array($fileActualExt, $allowed)) {
                header("Location: index.php?action=profile&id=".$id."&error=badtype");
                exit();
            } else {
                // On check s'il y a eu une erreur
                if ($fileError === 0) {
                    // On check que la taille ne soit pas trop grande
                    if ($fileSize > 1000000) {
                        header("Location: index.php?action=profile&id=".$id."&error=toobig");
                        exit();
                    } else {
                        // On créer un id perso pour les utilisateurs
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
                        header("Location: index.php?action=profile&id=".$id."&upload=success");
                        exit();
                    }
                } else {
                    header("Location: index.php?action=profile&id=".$id."&error=unknown");
                    exit();
                }
            }
        } else {
            header("Location: index.php");
            exit();
        }
    }

    public static function deletePicture() {

        if(isset($_POST['deleteimg_submit'])) {
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
                header("Location: index.php?action=profile&id=".$id."&error");
                exit();
            }
            // On indique que l'utilisateur n'a plus de photo de profil perso
            $stmt = Model::connect()->query("UPDATE users_gallery SET status=0 WHERE id_user='$id';");
            $_SESSION['picture'] = "public/img/profile/profiledefault.jpg";

            header("Location: index.php?action=profile&id=".$id."&delete=success");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    }

    public static function changeUsername($newusername) {
        $id = $_SESSION['userId'];
        $stmt = Model::connect()->prepare("UPDATE users SET username=? WHERE id='$id';");
        $stmt->execute([$newusername]);

        header("Location: index.php?action=profile&id=".$_SESSION['userId']."&username=success");
        exit();
    }

    public static function changePassword($newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $id = $_SESSION['userId'];
        $stmt = Model::connect()->prepare("UPDATE users SET password=? WHERE id='$id';");
        $stmt->execute([$hashedPassword]);

        header("Location: index.php?action=profile&id=".$_SESSION['userId']."&password=success");
        exit();
    }
}