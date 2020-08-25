
<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/userController.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/src/views/header.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/src/views/webcam.php';
  //  include $_SERVER['DOCUMENT_ROOT'] . '/src/views/footer.php';

    $userController = new userController();