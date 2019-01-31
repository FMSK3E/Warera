<?php

require_once "controllers/UserController.php";

class Router {

    public static function routerDirection() {

        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'signup-form') {
                UserController::signupForm();
            } else if ($_GET['action'] == 'signup') {
                UserController::signup();
            } else if ($_GET['action'] == 'login') {
                UserController::login();
            } else if ($_GET['action'] == 'logout') {
                UserController::logout();
            } else if ($_GET['action'] == 'profile') {
                UserController::profile();
            } else if ($_GET['action'] == 'upload-picture') {
                UserController::uploadPicture();
            } else if ($_GET['action'] == 'delete-picture') {
                UserController::deletePicture();
            }
        } else {
            UserController::index();
        }
    }
}