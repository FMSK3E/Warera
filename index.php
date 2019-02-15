<?php

session_start();

require_once 'models/Model.php';
Model::createDB();

require_once 'Router.php';
Router::routerDirection();

