const likeButton = document.getElementsByClassName("likeButton");
const likeCounter = document.getElementsByClassName("likeCounter");

getButtons();
getLikes();

function getButtons() {
    for (let i = 0; i < likeButton.length; i++) {
        likeButton[i].addEventListener("click", addLike)
        let data = new FormData();
        data.append('iid', likeButton[i].id);
        fetch('index.php/commentController/alreadyLiked', {
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            body: data
        }).then(response => response.json()
        ).then(
            success => likeButton[i].innerHTML = buttonText(success['liked'])
        ).catch(
            error => console.log(error)
        );
    }
}

function buttonText(liked) {
    if (liked === true)
        return "Unlike";
    else
        return "Like";
}

function getLikes() {
    for (let i = 0; i < likeCounter.length; i++) {
        let data = new FormData();
        data.append('iid', likeCounter[i].id);
        fetch('index.php/commentController/getLikes', {
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            body: data
        }).then(response => response.json()
        ).then(
            success => likeCounter[i].innerHTML = success['likes'] + " like(s)"
        ).catch(
            error => console.log(error)
        );
    }
}



function addLike() {        //TODO: ADD JSON RETURN ARRAY
    let data = new FormData();
    data.append('iid', this.id);
    fetch('index.php/commentController/addLike', {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        body: data
    }).then(res => res.text())          // convert to plain text
    getLikes();
    getButtons();
    /*    }).then(response => response.json()
        ).then(
            success => console.log(success)
        ).catch(
            error => console.log(error)
        );*/
}

