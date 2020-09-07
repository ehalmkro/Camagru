const likeButton = document.getElementsByClassName("likeButton");
const likeCounter = document.getElementsByClassName("likeCounter");
const commentField = document.getElementsByClassName("commentField");
const commentButton = document.getElementsByClassName("commentButton");
const comments = document.getElementsByClassName("comments")

listenCommentButton();
listenLikeButtons();

getLikes();
getComments();

function listenCommentButton() {
    for (let i = 0; i < commentButton.length; i++) {
        commentButton[i].addEventListener("click", addComment);
    }
}

function listenLikeButtons() {
    for (let i = 0; i < likeButton.length; i++) {
        likeButton[i].addEventListener("click", addLike);
        let data = new FormData();
        data.append('iid', likeButton[i].id.split(".")[1]);
        fetch('/index.php/commentController/alreadyLiked', {
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



function printComments(item, index, array) {
    let iid = item['iid'];
    let newDiv = document.createElement("div");

   newDiv.className = "commentDiv";
    let newContent = document.createElement("p");
    newContent.className ="commentText";
    let commentText = document.createTextNode(item['username'] + ": " + item['text']);
    newContent.appendChild(commentText);
    newDiv.appendChild(newContent);
    document.getElementById("comments." + iid).appendChild(newDiv);
    if (item['uid'] === sessionUid) {
        let deleteButton = document.createElement("p");
        deleteButton.innerText = "X";
        deleteButton.className = "deleteButton";
        newDiv.appendChild(deleteButton);
        deleteButton.addEventListener("click", res => deleteComment(item['cid']));
    }
}

function deleteComment(cid){
    if (!confirm("Delete comment?"))
        return;
    let data = new FormData();
    data.append('uid', sessionUid);
    data.append('cid', cid);
    fetch('/index.php/commentController/removeComment', {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        body: data
    }).then(response => response.json()
    ).then(
        success => location.reload()
    ).catch(
        error => console.log(error)
    );
}

function getComments() {
    for (let i = 0; i < comments.length; i++) {
        let data = new FormData();
        data.append('iid', comments[i].id.split(".")[1]);
        fetch('/index.php/commentController/getComments', {
            method: "POST",
            mode: "same-origin",
            credentials: "same-origin",
            body: data
        }).then(response => response.json()
        ).then(
            success =>{
                document.getElementById("commentCounter." + comments[i].id.split(".")[1]).innerText = success['comments'].length + " comment(s)";
                if (document.getElementsByClassName("comments"))
                    success['comments'].forEach(printComments);
            }
        ).catch(
            error => console.log(error)
        );
    }
}


function addComment() {
    let data = new FormData();
    data.append('iid', this.id.split(".")[1]);
    data.append('text', document.getElementById("commentField." + this.id.split(".")[1]).value);

    fetch('/index.php/commentController/addComment', {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        body: data
    }).then(response => response.json()
    ).then(
        success => {
            alert("Comment added");
            location.reload();
        }
    ).catch(
        error => console.log(error)
    );
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
        data.append('iid', likeCounter[i].id.split(".")[1]);
        fetch('/index.php/commentController/getLikes', {
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
    data.append('iid', this.id.split(".")[1]);
    fetch('/index.php/commentController/addLike', {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        body: data
    }).then(res => res.text())          // convert to plain text
    getLikes();
    listenLikeButtons();
    /*    }).then(response => response.json()
        ).then(
            success => console.log(success)
        ).catch(
            error => console.log(error)
        );*/
}
