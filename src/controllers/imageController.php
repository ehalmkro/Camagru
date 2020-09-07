<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/imageModel.php';
define("STICKER_DIR", "../../public/img/stickers/");

class imageController
{
    public $model;

    function __construct()
    {
        $this->model = new imageModel();
    }

    private function overlayStickers($file, $stickerArray)
    {
        ob_start();
        $mime = getimagesizefromstring($file);
        $image = imagecreatefromstring($file);
        $image = imagescale($image, 640, 480);

        $overlay = imagecreatetruecolor(640, 480);
        imagealphablending($overlay, true);
        imagesavealpha($overlay, true);
        $trans_colour = imagecolorallocatealpha($overlay, 0, 0, 0, 127);
        imagefill($overlay, 0, 0, $trans_colour);

        foreach ($stickerArray as $item) {
            $src = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . parse_url($item['filename'], PHP_URL_PATH));
            imagecopy($overlay, $src, 0, 0, 0, 0, 640, 480);
            imagedestroy($src);
        }
        imagecopy($image, $overlay, 0, 0, 0, 0, 640, 480);
        imagejpeg($image, NULL, 100);
        imagedestroy($overlay);
        $imageComposed = ob_get_clean();
        imagedestroy($image);
        return ($imageComposed);
    }


    public function uploadImage()
    {
        if (($uid = $this->checkUid()) == FALSE || !$_POST['file']) {
            echo json_encode([
                "status" => "fail",
                "error" => true,
                "message" => "No image"]);
            return ;
        }
        $file = $_POST['file'];
        $file = base64_decode((str_replace(' ', '+', $file)));
        if ($_POST['stickerArray']) {
            $file = $this->overlayStickers($file, json_decode($_POST['stickerArray'], true));
        }
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

    public function deleteImage()
    {
        if (($uid = $this->checkUid()) == FALSE || !$_POST['iid'])
            return ;
        if ($this->model->deleteImage($_POST['iid']))
            $response = array(
                "status" => "success",
                "error" => false,
                "message" => "Image deleted");
        else
            $response = array(
                "status" => "fail",
                "error" => true,
                "message" => "Image deletion FAILED");
        echo json_encode($response);

    }

    public function getPage()
    {
        return $this->model->page;
    }

    public function setPage($page)
    {
        $this->model->page = $page;
    }

    public function isLastPage(){
        return $this->model->lastPage;
    }

    public function displayImageByUser($uid)
    {
        return ($this->model->getImagesByUser($uid));
    }



    public function displayImageByIid($iid)
    {
        return $this->model->getImageByIid($iid);
    }

    public function checkUid()
    {
        if (!$_SESSION['uid']) {
            echo "Error";
            return FALSE;
        } else
            return $_SESSION['uid'];
    }
}