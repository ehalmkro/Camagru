const video = document.getElementById("videoElement");
const captureButton = document.getElementById("capturebutton");
const retakeButton = document.getElementById("retakebutton");
const uploadButton = document.getElementById("uploadbutton");
const cancelButton = document.getElementById("cancelbutton");
const img = document.getElementById('photo');
let dragItem = document.getElementById('sticker');
const container = document.getElementById("container");
let containerPosition = container.getBoundingClientRect();


let active = false;
let currentX;
let currentY;
let initialX;
let initialY;
let xOffset = 0;
let yOffset = 0;
let photoBlob;

container.addEventListener("touchstart", dragStart, false);
container.addEventListener("touchend", dragEnd, false);
container.addEventListener("touchmove", drag, false);

container.addEventListener("mousedown", dragStart, false);
container.addEventListener("mouseup", dragEnd, false);
container.addEventListener("mousemove", drag, false);

uploadButton.addEventListener("click", uploadImage, false);
cancelButton.addEventListener("click", cancelImage, false);

const upload = (file) => {

    let data = new FormData();
    data.append('file', file);
    data.append('user', 'test');
    fetch('../../index.php/imageController/uploadImage', {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        body: data
    })

        /* PLAINTEXT CONSOLE LOG FOR DEBUGGING
                .then(res => res.text())          // convert to plain text
                .then(text => console.log(text))  // then log it out */

        .then(response => response.json()
        ).then(
        success => console.log(success)
    ).catch(
        error => console.log(error)
    );
};

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

function dragStart(e) {
    if (e.type === "touchstart") {
        initialX = e.touches[0].clientX - xOffset;
        initialY = e.touches[0].clientY - yOffset;
    } else {
        initialX = e.clientX - xOffset;
        initialY = e.clientY - yOffset;
    }
    if (e.target === dragItem)
        active = true;
}

function dragEnd(e) {
    initialX = currentX;
    initialY = currentY;
    active = false;
}

function drag(e) {
    if (active) {
        e.preventDefault();
        containerPosition = container.getBoundingClientRect();
        if (e.type === "touchmove") {
            currentX = e.touches[0].clientX - initialX;
            currentY = e.touches[0].clientY - initialY;
        } else {
            currentX = containerPosition.right > xOffset + initialX ? e.clientX - initialX : containerPosition.right - initialX;
            currentY = containerPosition.bottom - 20 > yOffset + initialY ? e.clientY - initialY : containerPosition.bottom - initialY - 20;
        }
        xOffset = currentX;
        yOffset = currentY;
        setTranslate(currentX, currentY, dragItem);
    }
}

function setTranslate(xPos, yPos, el) {
    el.style.transform = "translate3d(" + xPos + "px, " + yPos + "px, 0)";

}

function takePicture(mediaStreamTrack, imageCapture) {
    imageCapture.takePhoto()
        .then(blob => {
            hideElements(video, captureButton);
            showElements(uploadButton);
            photoBlob = blob;
            img.src = URL.createObjectURL(blob);
            img.onload = () => {
                URL.revokeObjectURL(this.src);
            };
            showElements(img, retakeButton);
            retakeButton.addEventListener('click', function (ev) {
                ev.preventDefault();
                hideElements(img, uploadButton, retakeButton);
                showElements(video, captureButton);
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

if (navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({video: true})
        .then(gotMedia)
        .catch(function (err) {
            console.log("Something went wrong!");
        });
}

function hideElements(...args) {
    args.forEach(arg => arg.style.display = "none")
}

function showElements(...args) {
    args.forEach(arg => arg.style.display = "inline")
}
