<?php

class UserController {

    public static function signupForm() {
        require 'views/signup.php';
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }

    public static function signup() {
        UserModel::signup();
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
        UserModel::profile();
    }

    public static function uploadPicture() {
        UserModel::uploadPicture();
    }

    public static function deletePicture() {
        UserModel::deletePicture();
    }
}