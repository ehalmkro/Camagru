<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/commentModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/userModel.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/imageModel.php';

class commentController
{
    private $model;
    private $userModel;
    private $imageModel;

    function __construct()
    {
        $this->model = new commentModel();
        $this->userModel = new userModel();
        $this->imageModel = new imageModel();
    }

    function addComment()
    {
        if (empty($_POST['text'])) {
            echo json_encode(array(
                "status" => "fail",
                "error" => true,
                "message" => "Add text!"));
            return FALSE;
        }
        if (!$_SESSION['uid']) {
            echo json_encode(array(
                "status" => "fail",
                "error" => true,
                "message" => "Log in!"));
            return FALSE;
        }
        if (!$this->model->addComment($_POST['iid'], $_SESSION['uid'], $this->userModel->getUsername($_SESSION['uid']),
            preg_replace('/\s\s+/', ' ', $_POST['text'])))
            echo json_encode(array(
                "status" => "fail",
                "error" => true,
                "message" => "Couldn't add comment"));
        else {
            if ($_SESSION['uid'] !== $$this->imageModel->getImageByIid($_POST['iid'])['uid']
                && $this->userModel->getUserdata($_SESSION['uid'])['sendNotifications'] === 1) {
                mail($this->userModel->getUserdata($_SESSION['uid'])['email'],
                    "New comment",
                    "You just got a new comment!! Go and check it out at " .
                    $_SERVER['SERVER_PORT'] . "/src/views/view_image?iid=" . $_POST['iid']);
            }
            echo json_encode(array(
                "status" => "Success",
                "error" => false,
                "message" => "Comment added"));
        }
    }

    function getComments()
    {
        if (!$_POST['iid'])
            return FALSE;
        $array = $this->model->getComments($_POST['iid']);
        echo json_encode(array(
            "status" => "success",
            "error" => false,
            "comments" => $array));
        return TRUE;
    }

    function getCommentCount($iid)
    {
        $array = $this->model->getComments($iid);
        return count($array);
    }

    function addLike()
    {
        if (!$_SESSION['uid'] || !$_POST['iid']) {
            return FALSE;
        }
        if ($this->model->alreadyLiked($_POST['iid'], $_SESSION['uid']))
            return $this->removeLike();
        return $this->model->addLike($_POST['iid'], $_SESSION['uid'], $this->userModel->getUsername($_SESSION['uid']));
    }

    function alreadyLiked()
    {
        if ($this->model->alreadyLiked($_POST['iid'], $_SESSION['uid']))
            echo json_encode(array(
                "liked" => true));
        else
            echo json_encode(array(
                "liked" => false));
    }

    function removeComment()
    {
        if ((!$_SESSION['uid'] || !$_POST['cid']) || !$this->model->removeComment($_POST['cid'], $_SESSION['uid']))
            echo json_encode(array(
                "status" => "fail",
                "error" => true));
        else
            echo json_encode(array(
                "status" => "success",
                "error" => true));
        return;
    }

    function removeLike()
    {
        if (!$_SESSION['uid'] || !$_POST['iid'])
            return FALSE;
        return $this->model->removeLike($_POST['iid'], $_SESSION['uid']);
    }

    function getLikes()
    {
        $array = $this->model->getLikes($_POST['iid']);
        echo json_encode(array(
            "status" => "success",
            "error" => false,
            "likes" => count($array)));
        return;
    }
}