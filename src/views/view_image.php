<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/imageController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/commentController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/userController.php';

include $_SERVER['DOCUMENT_ROOT'] . '/src/views/header.php';

$imageController = new imageController();
$userController = new userController();
$commentController = new commentController();

$iid = $_GET['iid'];
$fromPage = $_GET['fromPage'];

$imageArray = $imageController->displayImageByIid($iid);

?>

<HTML>
<HEAD>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/css/style.css">
</HEAD>
<BODY>
<a href="/src/views/gallery.php?page=<? echo $fromPage ?>">Back to gallery</a>
<div class="imageDisplay">
    <? if (empty($imageArray)): ?>
        <p>Nothing here!</p>
    <? else: ?>
        <img id="viewImage" alt="Picture"
             src='/public/img/uploads/<? echo $imageArray['imageHash'] . '.jpg' ?>'
             title="<? echo $userController->returnUserName($imageArray['uid']) ?> at <? echo $imageArray['date'] ?> "/>
        <? if ($_SESSION['uid'] === $imageArray['uid']): ?>
            <button onclick="deleteImage()">Delete</button>
        <? endif; ?>
        <button class="likeButton" id="likeButton.<? echo $imageArray['iid'] ?>"></button>
        <div class="commentBar">
            <p class="likeCounter" id="likeCounter.<? echo $imageArray['iid'] ?>"> like(s)</p>
            <p class="commentCounter" id="commentCounter.<? echo $imageArray['iid'] ?>"></p>
            <div class="comments" id="comments.<? echo $imageArray['iid'] ?>"></div>
            <input type="text" class="commentField" placeholder="Comment"
                   id="commentField.<? echo $imageArray['iid'] ?>">
            <button class="commentButton" id="commentButton.<? echo $imageArray['iid'] ?>">Send</button>
        </div>
    <? endif; ?>
</div>
<script type="text/javascript">
    let sessionUid = "<? echo $_SESSION['uid']?>";
    let iid = "<? echo $imageArray['iid']?>";

    function deleteImage() {
        let data = new FormData();
        data.append('iid', iid);
        fetch('../../index.php/imageController/deleteImage', {
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            body: data
        })
            .then(response => response.json()
            ).then(
            success => window.location.replace("/index.php")
        ).catch(
            error => console.log(error)
        );
    }
</script>
<script src="/public/js/likecomment.js"></script>

</BODY>
</HTML>

