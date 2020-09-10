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
</HEAD>
<BODY>
<a href="/src/views/gallery.php?page=<? echo $fromPage ?>">Back to gallery</a>
<div class="imageDisplay galleryImage col-md bg-light border p-4 m-3 rounded">
    <? if (empty($imageArray)): ?>
        <p>Nothing here!</p>
    <? else: ?>
    <a href="/src/views/gallery.php?page=<? echo $fromPage ?>">
        <img id="viewImage" alt="Picture" class="rounded mx-auto d-block"
             src='/public/img/uploads/<? echo $imageArray['imageHash'] . '.jpg' ?>'
             title="<? echo $userController->returnUserName($imageArray['uid']) ?> at <? echo $imageArray['date'] ?> "/>
        </a>
        <? if ($_SESSION['uid'] === $imageArray['uid']): ?>
            <button class="btn-secondary btn-sm" onclick="deleteImage()">Delete</button>
        <? endif; ?>
        <button class="likeButton btn-primary btn-sm" id="likeButton.<? echo $imageArray['iid'] ?>"></button>
        <div class="commentBar d-flex justify-content-between p-5">
            <p class="likeCounter" id="likeCounter.<? echo $imageArray['iid'] ?>"> like(s)</p>
            <p class="commentCounter" id="commentCounter.<? echo $imageArray['iid'] ?>"></p>

        </div>
        <div class="comments text-center" id="comments.<? echo $imageArray['iid'] ?>"></div>
        <input type="text" class="commentField w-75" placeholder="Comment"
               id="commentField.<? echo $imageArray['iid'] ?>">
        <button class="commentButton btn-sm btn-primary " id="commentButton.<? echo $imageArray['iid'] ?>">Send</button>
        <? endif; ?>
</div>
<!--suppress JSUnusedLocalSymbols -->
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

