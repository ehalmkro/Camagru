<? if (!$_SESSION['uid']) // TODO: add previous images too this view!
header("Location: /index.php"); ?>

<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div id="webcamContainer">
        <video width="800" autoplay="true" id="videoElement"></video>
        <canvas id="canvas"></canvas>
        <img src="" id="photo" alt="The screen capture will appear in this box.">
        <div class = "toolBar">
            <button class="webcamButton" id="captureButton">Take photo</button>
            <button class="webcamButton" id="uploadButton">Add photo</button>
            <button class="webcamButton" id="retakeButton">Retake photo</button>
            <button class="webcamButton" id="selectFileButton">Select image file from device</button>
            <input type="file" id="fileInput">
            <button class="webcamButton" id="previewFileButton">Preview file</button>
            <button class="webcamButton" id="cancelButton">Close</button>
        </div>
        <div id="stickerBar">
            <ul>
                <li><input type="checkbox" id="cb1" />
                    <label for="cb1"><img id = "cb1img" src="/public/img/stickers/poop.png" /></label>
                </li>
                <li><input type="checkbox" id="cb2" />
                    <label for="cb2"><img id = "cb2img" src="/public/img/stickers/hat.png" /></label>
                </li>
                <li><input type="checkbox" id="cb3" />
                    <label for="cb3"><img id = "cb3img" src="/public/img/stickers/hive.png" /></label>
                </li>
                <li><input type="checkbox" id="cb4" />
                    <label for="cb4"><img id = "cb4img" src="/public/img/stickers/tornio.png" /></label>
                </li>
            </ul>
        </div>
    </div>
<script src="/public/js/webcam.js"></script>
</body>
</html>