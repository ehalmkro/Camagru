
<?php
    session_start();
    include $_SERVER['DOCUMENT_ROOT'] . '/src/views/header.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/src/views/webcam.php';

    echo "hello " . $user;