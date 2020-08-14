<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/imageModel.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/Core.class.php';

class imageModel
{
    private $pdo;

    public function __construct()
    {
        $core = Core::getInstance();
        $this->pdo = $core->pdo;
    }

    public function addImage($uid, $file)
    {
        $filename = uniqid("img_");
        if (filesize($file) > 5120 || !filesize($file)) {
            echo "Error, file size too big";
            return FALSE;
        }
        try {
            $stmt = $this->pdo->prepare("INSERT INTO 
                                    images (uid, imageHash) 
                                    VALUES (?, ?)");
            $stmt->execute([$uid, $filename]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return FALSE;
        }
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/public/img/uploads/" . $filename . ".jpg", $file);
        $response = [
            "status" => "success",
            "error" => false,
            "message" => trim($_SERVER['PATH_INFO'], '/')];
        echo json_encode($response);
        return TRUE;
    }
}