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
    </HEAD>
    <BODY>
    <div class="container">
        <? if (empty($image_array)): ?>
            <p>Seems to be a bit empty here, why don't you add some stuff?</p>
        <? else: { ?>
            <? foreach ($image_array as $k => $innerArray): ?>
                <div class="galleryImage col-md bg-light border p-4 m-3 rounded">
                    <!--suppress HtmlUnknownTarget -->
                    <a href="/index.php/viewImage?iid=<? echo $innerArray['iid'] ?>&fromPage=<? echo $page ?>"><img
                                alt="user image" id="userImage" class="rounded mx-auto d-block"
                                src='/public/img/uploads/<? echo $innerArray['imageHash'] . '.jpg' ?>'/></a>
                    <p class="text-center"> by user <? echo $userController->returnUserName($innerArray['uid']) ?>
                        at <? echo $innerArray['date'] ?> </p>

                    <div class="commentBar d-flex justify-content-between p-5">
                        <button class="likeButton btn-sm btn-primary" id="likeButton.<? echo $innerArray['iid'] ?>"></button>
                        <p class="likeCounter d-flex justify-content-between" id="likeCounter.<? echo $innerArray['iid'] ?>"> like(s)</p>
                        <!--suppress HtmlUnknownTarget -->
                        <a href="/index.php/viewImage?iid=<? echo $innerArray['iid'] ?>&fromPage=<? echo $page ?>" class="commentCounter d-flex justify-content-between"
                           id="commentCounter.<? echo $innerArray['iid'] ?>"><? echo $commentController->getCommentCount($innerArray['iid']); ?>
                            comment(s)</a>
                    </div>
                </div>
            <? endforeach;
        } endif; ?>
    </div>
    <? if ($page > 0): ?>
        <a href="/src/views/gallery.php/?page=<? echo $imageController->getPage() - 1 ?>"
           class="button">Previous page</a>
    <? endif; ?>
    <? if (!$imageController->model->lastPage): ?>
        <a href="/src/views/gallery.php/?page=<? echo $imageController->getPage() + 1 ?>" class="button">Next page</a>
    <? endif; ?>

    <script src="/public/js/infinite.js"></script>
    <script type="text/javascript">let sessionUid = "<?php echo $_SESSION['uid']?>"</script>
    <script src="/public/js/likecomment.js"></script>

    </BODY>

    </HTML>
<?php include("footer.php");