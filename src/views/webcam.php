<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div id="webcamContainer">
        <video autoplay="true" id="videoElement"></video>
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
    </div>
<script src="/public/js/webcam.js"></script>
</body>
</html>