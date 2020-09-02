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
        $overlay = imagecreatetruecolor($mime[0], $mime[1]);
       foreach ($stickerArray as $item)
        {
           // echo json_encode($_SERVER['DOCUMENT_ROOT'] . parse_url($item['filename'], PHP_URL_PATH));
            $src = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . parse_url($item['filename'], PHP_URL_PATH));
            $src = imagescale($src, $item['w'], $item['h']);
            imagecopyresampled($overlay, $src, $item['xPos'], $item['yPos'], 0, 0, $item['h'], $item['w'], $mime[1], $mime[0]);
            imagedestroy($src);
        }

       imagejpeg($overlay);
       imagedestroy($overlay);
       $overlayComposed = ob_get_clean();
       file_put_contents("esa.jpg", $overlayComposed);
      //  imagecopymerge($image, $overlay, 0, 0, 0,0, $mime[0], $mime[1], 0);
        //imagedestroy($overlay);
        //return (imagejpeg($image));
   return $file;
    }


    public function uploadImage()
    {
        if (($uid = $this->checkUid()) == FALSE || !$_POST['file'])
            echo json_encode([
                "status" => "fail",
                "error" => true,
                "message" => "No image"]);
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

    public function displayImage($uid)
    {
        return ($this->model->getImages($uid));
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