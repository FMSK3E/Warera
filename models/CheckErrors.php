<?php

class CheckErrors extends Model {

    public static function signupErrors($username, $email, $password, $passwordRepeat) {
        // Si des paramètres sont vides, on renvoie une erreur
        if (empty($username) || empty($email) || empty($password) || empty($passwordRepeat)) {
            header("Location: index.php?action=signup_form&error=emptyfields&uid=".$username."&email=".$email);
            exit();
        // Si l'email et l'username ne sont pas bons, on renvoie une erreur
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            header("Location: index.php?action=signup_form&error=invalidemailuid");
            exit();
        // Si l'email n'est pas bon, on renvoie une erreur
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: index.php?action=signup_form&error=invalidemail&uid=".$username);
            exit();
        // Si l'username n'est pas bon, on renvoie une erreur
        } else if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            header("Location: index.php?action=signup_form&error=invaliduid&email=".$email);
            exit();
        // Si le mdp et la répétition de mdp ne correspondent pas, on renvoie une erreur
        } else if ($password != $passwordRepeat) {
            header("Location: index.php?action=signup_form&error=password&uid=".$username."&email=".$email);
            exit();
        }
    }
}