<?php

require_once "controllers/UserController.php";

class Router {

    public static function routerDirection() {

        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'signup_form') {
                UserController::signupForm();
            } else if ($_GET['action'] == 'signup') {
                UserController::signup($_POST['uid'], $_POST['email'], $_POST['pwd'], $_POST['repwd']);
            } else if ($_GET['action'] == 'login') {
                UserController::login();
            } else if ($_GET['action'] == 'logout') {
                UserController::logout();
            } else if ($_GET['action'] == 'profile') {
                UserController::profile($_GET['id']);
            } else if ($_GET['action'] == 'upload_picture') {
                UserController::uploadPicture();
            } else if ($_GET['action'] == 'delete_picture') {
                UserController::deletePicture();
            } else if ($_GET['action'] == 'change_username') {
                UserController::changeUsername($_POST['new_username'], $_POST['password']);
            } else if ($_GET['action'] == 'change_password') {
                UserController::changePassword($_POST['new_password'], $_POST['new_repassword'], $_POST['password']);
            }
        } else {
            UserController::index();
        }
    }
}