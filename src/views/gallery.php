<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/imageController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/commentController.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/userController.php';
include $_SERVER['DOCUMENT_ROOT'] . '/src/views/header.php';

$imageController = new imageController();
$userController = new userController();
$commentController = new commentController();

$image_array = $imageController->displayImageByUser(NULL);

$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));
$page = $_GET['page'];
$page = $page == NULL ? 0 : $page;
$imageController->model->page = $page;
$image_array = $imageController->displayImageByUser(NULL);

?>

    <HTML>
    <HEAD>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
              integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX"
              crossorigin="anonymous">
        <link rel="stylesheet" href="/public/css/style.css">
    </HEAD>
    <BODY>
    <div class="gallery">
        <? if (empty($image_array)): ?>
            <p>Seems to be a bit empty here, why don't you add some stuff?</p>
        <? else: { ?>
            <? foreach ($image_array as $k => $innerArray): ?>
                <div class="galleryImage">
                    <a href="/index.php/viewImage?iid=<? echo $innerArray['iid'] ?>&fromPage=<? echo $page ?>"><img
                                id="userImage"
                                src='/public/img/uploads/<? echo $innerArray['imageHash'] . '.jpg' ?>'/></a>
                    <p> by user <? echo $userController->returnUserName($innerArray['uid']) ?>
                        at <? echo $innerArray['date'] ?> </p>
                    <button class="likeButton" id="likeButton.<? echo $innerArray['iid'] ?>"></button>
                    <div class="commentBar">
                        <p class="likeCounter" id="likeCounter.<? echo $innerArray['iid'] ?>"> like(s)</p>
                        <p class="commentCounter"
                           id="commentCounter.<? echo $innerArray['iid'] ?>"><? echo $commentController->getCommentCount($innerArray['iid']); ?>
                            comment(s)</p>
                    </div>
                </div>
            <? endforeach;
        } endif; ?>
    </div>
    <? if ($page > 0): ?>
        <a href="/src/views/gallery.php/?page=<? echo $imageController->getPage() - 1 ?>"
           class="button">Previous page</a>
    <? endif; ?>
    <? if (!$imageController->model->lastPage): // TODO: USER GETTER FOR THIS ?>
        <a href="/src/views/gallery.php/?page=<? echo $imageController->getPage() + 1 ?>" class="button">Next page</a>
    <? endif; ?>

    <script src="/public/js/infinite.js"></script>
    <script type="text/javascript">let sessionUid = "<?php echo $_SESSION['uid']?>"</script>
    <script src="/public/js/likecomment.js"></script>

    </BODY>

    </HTML>
<?php include("footer.php");