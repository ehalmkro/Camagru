const video = document.getElementById("videoElement");
const captureButton = document.getElementById("captureButton");
const retakeButton = document.getElementById("retakeButton");
const uploadButton = document.getElementById("uploadButton");
const cancelButton = document.getElementById("cancelButton");
const selectFileButton = document.getElementById("selectFileButton");
const previewFileButton = document.getElementById("previewFileButton");
const fileInput = document.getElementById("fileInput");
const img = document.getElementById('photo');
const container = document.getElementById('webcamContainer');
let previewPic;


let stickerArray = [];

const canvas = document.createElement("canvas");
canvas.setAttribute('width', "640");
canvas.setAttribute('height', "480");
const ctx = canvas.getContext("2d");

container.appendChild(canvas);

let photoBlob;

const elementList = document.querySelectorAll("input");

for (let i = 0; i < elementList.length; i++)
    elementList[i].addEventListener("change", e => {
        if (e.target.checked)
            addSticker(elementList[i].id);
        else
            removeSticker(elementList[i].id);
    }, false);

selectFileButton.addEventListener("click", selectFile, false);
uploadButton.addEventListener("click", uploadImage, false);
cancelButton.addEventListener("click", cancelImage, false);

function drawImage() {

    ctx.clearRect(0, 0, 0, 0);
    ctx.drawImage(previewPic, 0, 0);
    let sticker = [];
    for (let i = 0; i < stickerArray.length; i++) {
        sticker[i] = new Image();
        let previewSource = document.getElementById(stickerArray[i].stickerId + "img").src;
        sticker[i].src = previewSource.replace(".png", "_filter.png");
        ctx.drawImage(sticker[i], 0, 0, 640, 480);
    }

}

function addSticker(id) {
    ctx.drawImage(previewPic, 0, 0);
    let previewSource = document.getElementById(id + "img").src;
    let filename = previewSource.replace(".png", "_filter.png");
    stickerArray.push({"stickerId": id, "filename": filename});
    drawImage();
}

function removeSticker(id) {

    ctx.clearRect(0, 0, 0, 0);
    ctx.drawImage(previewPic, 0, 0);
    for (let i = 0; i < stickerArray.length; i++) {
        if (stickerArray[i].stickerId === id)
            stickerArray.splice(i, 1);
    }
    drawImage();
}

const upload = (file) => {

    let data = new FormData();
    data.append('file', file);
    data.append('stickerArray', JSON.stringify(stickerArray));
    fetch('../../index.php/imageController/uploadImage', {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        body: data
    })

        // PLAINTEXT CONSOLE LOG FOR DEBUGGING
        .then(res => res.text())          // convert to plain text
        .then(text => console.log(text))  // then log it out

    /*        .then(response => response.json()
            ).then(
            success => console.log(success)
        ).catch(
            error => console.log(error)
        );*/
};


function selectFile() {
    hideElements(selectFileButton, captureButton, video); // TODO: PAUSE VIDEO PROPERLY
    showElements(previewFileButton, fileInput, img);
    img.style.visibility = 'hidden';
    previewFileButton.addEventListener("click", res => {
        const selectedFile = document.getElementById("fileInput").files[0];
        if (selectedFile === undefined || selectedFile.size > 5120000 ||
            (selectedFile.type !== 'image/png' && selectedFile.type !== 'image/jpeg')) {
            console.log(selectedFile.type);
            alert("Invalid file!");
        } else {
            let reader = new FileReader();
            reader.readAsDataURL(selectedFile);
            img.style.visibility = 'visible';
            reader.addEventListener("load", function () {
                // convert image file to base64 string
                img.src = reader.result;
                photoBlob = selectedFile;
                showElements(uploadButton);
            }, false);
            reader.readAsDataURL(selectedFile);
        }
    });
}

function uploadImage() {
    let reader = new FileReader();
    reader.readAsDataURL(photoBlob);
    reader.onload = function () {
        let base64String = reader.result.split(',')[1];
        // console.log(base64String);
        upload(base64String);
    };
    window.opener.location.reload();
    window.close();
    // TODO: back to default view after this
}

function cancelImage() {
    window.close();
}


function takePicture(mediaStreamTrack, imageCapture) {
    imageCapture.takePhoto()
        .then(blob => {
            hideElements(video, captureButton, selectFileButton);
            showElements(uploadButton);
            photoBlob = blob;
            previewPic = new Image();
            previewPic.src = URL.createObjectURL(blob);
            ctx.drawImage(video, 0, 0);
            drawImage();
            showElements(img, retakeButton);
            retakeButton.addEventListener('click', function (ev) {
                ev.preventDefault();
                hideElements(img, uploadButton, retakeButton);
                showElements(video, captureButton, selectFileButton);
            }, false);
        })
        .catch(error => console.error('takePhoto() error:', error));
}

function gotMedia(mediaStream) {
    video.srcObject = mediaStream;
    video.play();
    const mediaStreamTrack = mediaStream.getVideoTracks()[0];
    const imageCapture = new ImageCapture(mediaStreamTrack);
    captureButton.addEventListener('click', function (ev) {
        ev.preventDefault();
        takePicture(mediaStreamTrack, imageCapture);
    }, false);
}

function noWebcam() {
    hideElements(video, captureButton);
    showElements(img);
    img.src = "/public/img/resources/noWebcam.png";
}

if (navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({  video: {
            width: { exact: 640 },
            height: { exact: 480 } } }
        )
        .then(gotMedia)
        .catch(function (err) {
            noWebcam();
        });
}

function hideElements(...args) {
    args.forEach(arg => arg.style.display = "none")
}

function showElements(...args) {
    args.forEach(arg => arg.style.display = "inline")
}
