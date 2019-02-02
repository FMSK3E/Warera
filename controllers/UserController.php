<?php

class UserController {

    public static function signupForm() {
        require 'views/signup.php';
    }

    public static function signup($username, $email, $password, $passwordRepeat) {
        // Pour s'assurer que l'user soit sur la page de manière normale
        if (isset($_POST['signup_submit'])) {
            CheckErrors::signupErrors($username, $email, $password, $passwordRepeat);
            UserModel::signup($username, $email, $password);
        } else {
            require 'views/index.php';
        }
    }

    public static function login() {
        UserModel::login();
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }

    public static function logout() {
        UserModel::logout();
    }

    public static function index() {
        require 'views/index.php';
    }

    public static function profile($id) {
        $userInfos = UserModel::findUserInfos($id);
        require 'views/profile.php';
    }

    public static function uploadPicture() {
        UserModel::uploadPicture();
    }

    public static function deletePicture() {
        UserModel::deletePicture();
    }

    public static function changeUsername($newusername, $password) {
        if(isset($_POST['changeusername_submit'])) {
            CheckErrors::changeUsernameErrors($newusername, $password);
            UserModel::changeUsername($newusername);
        } else {
            header("Location: index.php");
            exit();
        }
    }

    public static function changePassword($newPassword, $newPasswordRepeat, $oldPassword) {
        CheckErrors::changePasswordErrors($newPassword, $newPasswordRepeat, $oldPassword);
        UserModel::changePassword($newPassword);
    }
}