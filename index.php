<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/userController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/imageController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/commentController.php';

$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$controller = $request[0];
$method = $request[1];

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
        if (!isset($_SESSION['uid'])) {
            include $_SERVER['DOCUMENT_ROOT'] . '/src/views/gallery.php';
            break;
        }
        include $_SERVER['DOCUMENT_ROOT'] . '/src/views/webcam.php';
        break;
    }

    case 'viewImage':
    {
        if (!isset($_SESSION['uid'])) {
            $_GET['login'] = "fail";
            include $_SERVER['DOCUMENT_ROOT'] . '/src/views/gallery.php';
            break;
        }
        include $_SERVER['DOCUMENT_ROOT'] . '/src/views/view_image.php';
        break;
    }

    case 'resetPassword':
    {
        $_SESSION['uid'] = $request[1];
        $_SESSION['confirmationCode'] = $request[2];
        include $_SERVER['DOCUMENT_ROOT'] . '/src/views/reset_password.php';
        break;
    }

    case 'forgotPassword':
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/src/views/forgot_password.php';
        break;
    }
    case 'userSettings':
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/src/views/user_settings.php';
        break;
    }

    case 'login':
    {
        include $_SERVER['DOCUMENT_ROOT'] . '/src/views/loginpage.php';
        break;
    }

    default:
        include $_SERVER['DOCUMENT_ROOT'] . '/src/views/gallery.php';
}
