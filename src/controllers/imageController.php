<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/imageModel.php';

class imageController
{
    private $model;

    function __construct()
    {
        $this->model = new imageModel();
    }

    public function handleRequest()
    {
        $op = trim($_SERVER['PATH_INFO'], '/');
        switch ($op) {
            case 'uploadImage':
                $this->uploadImage();
        }

    }

    public function uploadImage()
    {
        if ($this->checkUid() == FALSE || !$_POST['file'])
            return FALSE;
        $file = $_POST['file'];
        $file = base64_decode((str_replace(' ','+',$file)));
        if (!$this->model->addImage($_POST['uid'], $file))
            $response = [
            "status" => "success",
            "error" => false,
            "message" => "Image upload succeeded"];
        else
            $response = [
                "status" => "fail",
                "error" => true,
                "message" => "Image upload FAILED"];
        echo json_encode($response);

    }

    public function checkUid()
    {
        if (!$_SESSION['uid']) {
            echo "Error";
            return FALSE;
        }
        else
            return $_SESSION['uid'];
    }
}