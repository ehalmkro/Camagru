window.onscroll = infiniteScroll;
let isExecuted = false;

function infiniteScroll() {
    if (window.scrollY > (document.body.offsetHeight - window.outerHeight) && !isExecuted) {
        isExecuted = true;

       // console.log("Working...");

        setTimeout(() => {
            isExecuted = false;
        }, 1000);
    }
}