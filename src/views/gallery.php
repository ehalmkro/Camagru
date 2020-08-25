<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/imageController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/userController.php';
include $_SERVER['DOCUMENT_ROOT'] . '/src/views/header.php';

$imageController = new imageController();
$userController = new userController();

$image_array = $imageController->displayImage(NULL);

$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$page = $request[0];
$page = $page == NULL ? 0 : $page;
$imageController->model->page = $page;
$image_array = $imageController->displayImage(NULL);
?>

<HTML>
<HEAD>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/css/style.css">
</HEAD>
<BODY>
<div class="gallery">
    <? foreach ($image_array as $k => $innerArray): ?>
        <div class="imageDiv">
            <img id="userImage" src='/public/img/uploads/<? echo $innerArray['imageHash'] . '.jpg' ?>'/>
            <p> by user <? echo $userController->getUserName($innerArray['uid']) ?>
                at <? echo $innerArray['date'] ?> </p>
            <button class="likeButton" id="<? echo $innerArray['iid']?>"></button>
            <p class="likeCounter" id="<? echo $innerArray['iid']?>"> like(s)</p>
        </div>
    <? endforeach; ?>
</div>
<? if ($page > 0): ?>
    <a href="/src/views/gallery.php/<? echo $imageController->model->page - 1 // TODO: USER GETTER FOR THIS ?>" class="button">Previous page</a>
<? endif; ?>
<? if (!$imageController->model->lastPage): // TODO: USER GETTER FOR THIS ?>
    <a href="/src/views/gallery.php/<? echo $imageController->model->page + 1 ?>" class="button">Next page</a>
<? endif; ?>

<script src="/public/js/infinite.js"></script>
<script src="/public/js/like.js"></script>

</BODY>

</HTML>
