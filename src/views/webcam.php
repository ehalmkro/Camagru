<html>
<body>
<div id="outerContainer">
    <div id="container" style="position: absolute;">
        <video autoplay="true" id="videoElement"></video>
        <img id="photo" alt="The screen capture will appear in this box." style="display: none;">
        <div id="overlay" style="position:absolute; top:50; width:50%; z-index:25; text-align:center;">
            <img style="position:absolute;" draggable="true" id="sticker" src="../public/img/stickers/poop.png" width="75px"/>
        </div>

        <div>
            <button class="button" id="capturebutton" style="margin: auto">Take photo</button>
            <button id="uploadbutton" style="margin:auto; display: none;">Upload photo</button>
            <button id="retakebutton" style="margin: auto; display: none;">Retake photo</button>
        </div>
    </div>
</div>
<canvas id="canvas">
</canvas>
<div class="output">

</div>
<script src="../../public/js/webcam.js"></script>
</body>
</html>

<?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/imageController.php';

    $imgController = new imageController();
    $imgController->handleRequest();