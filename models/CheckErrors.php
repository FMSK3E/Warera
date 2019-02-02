<?php

class CheckErrors extends Model {

    public static function signupErrors($username, $email, $password, $passwordRepeat) {
        // Si des champs sont vides
        if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
            header("Location: index.php?action=signup_form&error=emptyfields&uid=".$username."&email=".$email);
            exit();
        // Si l'email et l'username ne sont pas bons
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            header("Location: index.php?action=signup_form&error=invalidemailuid");
            exit();
        // Si l'email n'est pas bon
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: index.php?action=signup_form&error=invalidemail&uid=".$username);
            exit();
        // Si l'username n'est pas bon
        } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            header("Location: index.php?action=signup_form&error=invaliduid&email=".$email);
            exit();
        // Si le mdp et la répétition de mdp ne correspondent pas
        } else if ($password != $passwordRepeat) {
            header("Location: index.php?action=signup_form&error=password&uid=".$username."&email=".$email);
            exit();
        }
    }

    public static function changeUsernameErrors($newusername, $password) {
        if(isset($_POST['changeusername_submit'])) {
            // Si des champs sont vides
            if (empty($newusername) || empty($password)) {
                header("Location: index.php?action=profile&id=".$_SESSION['userId']."&profile_action=username&error=emptyfields&new_username=".$newusername);
                exit();
            // Si l'username n'est pas bon
            } else if (!preg_match("/^[a-zA-Z0-9]*$/", $newusername)) {
                header("Location: index.php?action=profile&id=".$_SESSION['userId']."&profile_action=username&error=invaliduid");
                exit();
            } else {
                // On check le mdp
                $stmt = Model::connect()->query("SELECT * FROM users WHERE id=".$_SESSION['userId']);
                $datas = $stmt->fetch(PDO::FETCH_ASSOC);
                $pwdCheck = password_verify($password, $datas['password']);
                if ($pwdCheck == false) {
                    header("Location: index.php?action=profile&id=".$_SESSION['userId']."&profile_action=username&error=invalidpassword");
                    exit();
                } else {
                    // On check si l'username est pas déjà pris
                    $stmt = Model::connect()->query("SELECT * FROM users WHERE username='$newusername';");
                    $datas = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($datas != NULL) {
                        header("Location: index.php?action=profile&id=".$_SESSION['userId']."&profile_action=username&error=usernametaken");
                        exit();
                    }
                }
            }
        } else {
            header("Location: index.php");
            exit();
        }
    }

    public static function changePasswordErrors($newPassword, $newPasswordRepeat, $oldPassword) {
        // Si des champs sont vides
        if (empty($newPassword) || empty($newPasswordRepeat) || empty($oldPassword)) {
            header("Location: index.php?action=profile&id=".$_SESSION['userId']."&profile_action=password&error=emptyfields");
            exit();
        // Si le mdp et la répétition de mdp ne correspondent pas
        } else if ($newPassword != $newPasswordRepeat) {
            header("Location: index.php?action=profile&id=".$_SESSION['userId']."&profile_action=password&error=password");
            exit();
        } else if ($newPassword == $oldPassword) {
            header("Location: index.php?action=profile&id=".$_SESSION['userId']."&profile_action=password&error=samepassword");
            exit();
        } else {
            // On check le mdp
            $stmt = Model::connect()->query("SELECT * FROM users WHERE id=".$_SESSION['userId']);
            $datas = $stmt->fetch(PDO::FETCH_ASSOC);
            $pwdCheck = password_verify($oldPassword, $datas['password']);
            if ($pwdCheck == false) {
                header("Location: index.php?action=profile&id=".$_SESSION['userId']."&profile_action=password&error=invalidpassword");
                exit();
            }
        }
    }
}