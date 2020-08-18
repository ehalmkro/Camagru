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

    public function uploadImage()
    {
        if (($uid = $this->checkUid()) == FALSE || !$_POST['file'])
            return json_encode([
                "status" => "fail",
                "error" => true,
                "message" => "No image"]);
        $file = $_POST['file'];
        $file = base64_decode((str_replace(' ','+',$file)));
        if ($this->model->addImage($uid, $file))
            $response = array(
            "status" => "success",
            "error" => false,
            "message" => "Image upload succeeded");
        else
            $response = array(
                "status" => "fail",
                "error" => true,
                "message" => "Image upload FAILED");
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