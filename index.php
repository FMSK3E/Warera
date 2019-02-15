<?php

session_start();

require_once 'models/Model.php';
Model::createDB();

require_once 'Router.phpa';
Router::routerDirection();

