<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/commentModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/userModel.class.php';

class commentController
{
    private $model;
    private $userModel;

    function __construct()
    {
        $this->model = new commentModel();
        $this->userModel = new userModel();
    }

    function addComment()
    {
        if (empty($_POST['text'])) {
            echo "Add text to comment";
            return FALSE;
        }
        if (!$_SESSION['uid']) {
            return FALSE;
        }
        // TODO: add timestamp check so no spam or smth...
        return $this->model->addComment($_POST['iid'], $_SESSION['uid'], $this->userModel->getUsername($_SESSION['uid']),
            preg_replace('/\s\s+/', ' ', $_POST['text']));
    }

    function getComments()
    {
        if (!$_POST['iid'])
            return FALSE;
        return $this->model->getComments($_POST['iid']);
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