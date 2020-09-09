<? if (!$_SESSION['uid'])
    header("Location: /index.php");
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/imageController.php';
$imageController = new imageController();
$image_array = $imageController->displayImageByUser($_SESSION['uid']);
?>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
<div id="container">
    <div class="row">
        <div class="col" id="webcamContainer">
            <video width="800" autoplay="true" id="videoElement"></video>
            <canvas id="canvas"></canvas>
            <img src="" id="photo" alt="The screen capture will appear in this box.">
            <div class="toolBar d-flex justify-content-center">
                <button class="webcamButton btn-primary" id="captureButton">Take photo</button>
                <button class="webcamButton btn-primary" id="uploadButton">Add photo</button>
                <button class="webcamButton btn-primary" id="retakeButton">Retake photo</button>
                <button class="webcamButton btn-primary" id="selectFileButton">Select image file from device</button>
                <input type="file" id="fileInput">
                <button class="webcamButton btn-primary" id="previewFileButton">Preview file</button>
                <button class="webcamButton btn-primary" id="cancelButton">Close</button>
            </div>
        </div>
        <div class="col border" height="10%">
            <? if (!empty($image_array))
                foreach ($image_array as $k => $innerArray): ?>
                    <img alt="user image" width="150px"
                         src='/public/img/uploads/<? echo $innerArray['imageHash'] . '.jpg' ?>'/>
                <? endforeach; ?>
        </div>
    </div>
    <div class="row toolBar d-flex justify-content-center" id="stickerBar">
        <ul>
            <li><input type="checkbox" id="cb1"/>
                <label for="cb1"><img id="cb1img" src="/public/img/stickers/poop.png"/></label>
            </li>
            <li><input type="checkbox" id="cb2"/>
                <label for="cb2"><img id="cb2img" src="/public/img/stickers/hat.png"/></label>
            </li>
            <li><input type="checkbox" id="cb3"/>
                <label for="cb3"><img id="cb3img" src="/public/img/stickers/hive.png"/></label>
            </li>
            <li><input type="checkbox" id="cb4"/>
                <label for="cb4"><img id="cb4img" src="/public/img/stickers/tornio.png"/></label>
            </li>
        </ul>
    </div>
</div>
<script src="/public/js/webcam.js"></script>
</body>
</html>