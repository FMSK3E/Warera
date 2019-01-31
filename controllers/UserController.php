<?php

class UserController {

    public static function signupForm() {
        require 'views/signup.php';
    }

    public static function signup($username, $email, $password, $passwordRepeat) {
        // Pour s'assure que l'user soit sur la page de manière normale
        if (isset($_POST['signup_submit'])) {
            CheckErrors::signupErrors($username, $email, $password, $passwordRepeat);
            UserModel::signup($username, $email, $password, $passwordRepeat);
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

    public static function profile() {
        require 'views/profile.php';
    }

    public static function uploadPicture() {
        UserModel::uploadPicture();
    }

    public static function deletePicture() {
        UserModel::deletePicture();
    }
}