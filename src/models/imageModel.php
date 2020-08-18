<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/Core.class.php';

class imageModel
{
    private $pdo;
    public $page;
    private $maxPages;
    private $numRows;
    private $perPage;
    private $dataArray;

    public function __construct()
    {
        $core = Core::getInstance();
        $this->pdo = $core->pdo;
        $this->page = 0;
        $this->numRows = 0;
        $this->maxPages = 0;
        $this->perPage = 5;
    }

    public function addImage($uid, $file)
    {
        $filename = uniqid("img_");
        if (strlen($file) > 5120000 || !strlen($file)) {
            return FALSE;
        }
        try {
            $stmt = $this->pdo->prepare("INSERT INTO 
                                    images (uid, imageHash) 
                                    VALUES (?, ?)");
            $stmt->execute([$uid, $filename]);
        } catch (PDOException $e) {
            //   echo "Error: " . $e->getMessage();
            return FALSE;
        }
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/public/img/uploads/" . $filename . ".jpg", $file);
        return TRUE;
    }

    public function getImages($uid)
    {
        $limit = $this->page * $this->perPage;
        if (empty($uid))
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM images");
        else
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM images WHERE uid = ?");
        empty($uid) ? $stmt->execute() : $stmt->execute([$uid]);
        $queryData = $stmt->fetch();
        $this->numRows = $queryData[0];
        if ($this->numRows <= 0) {
            $this->page = 0;
            return NULL;
        }
        if (empty($uid))
            $stmt = $this->pdo->prepare("SELECT * FROM images LIMIT $limit, $this->perPage");
        else
            $stmt = $this->pdo->prepare("SELECT * FROM images WHERE uid = ? LIMIT $limit, $this->perPage");
        empty($uid) ? $stmt->execute() : $stmt->execute([$uid]);
        $this->dataArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return $this->dataArray;
    }
}