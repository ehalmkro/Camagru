<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/imageController.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/userController.php';

    $imageController = new imageController();
    $userController = new userController();

    $image_array = $imageController->displayImage(NULL);

    foreach ($image_array as $k => $innerArray) {
        echo '<img src=\'' . '/public/img/uploads/' . $innerArray['imageHash'] . '.jpg\'' . '/>';
        echo 'By user ' . $userController->getUserName($innerArray['uid']);
    }
