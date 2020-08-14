<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/userController.php';
    $controller = new userController();
    $controller->handleRequest();

