<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/userController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/imageController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/commentController.php';

$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$controller = $request[0];
$method = $request[1];
$argument = $request[2];

switch ($controller) {
    case 'userController':
    {
        $ctrl = new userController();
        $ctrl->$method();
        break;
    }
    case 'imageController':
    {
        $ctrl = new imageController();
        $ctrl->$method();
        break;
    }
    case 'commentController':
    {
        $ctrl = new commentController();
        $ctrl->$method();
        break;
    }

    case 'takePicture':
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/src/views/webcam.php';
        break;
    }
    default:
        if (isset($_SESSION['uid']))
            include $_SERVER['DOCUMENT_ROOT'] . '/src/views/gallery.php';
        else
            include $_SERVER['DOCUMENT_ROOT'] . '/src/views/homepage.php';
}
?>