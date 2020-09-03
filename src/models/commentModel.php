<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/Core.class.php';

//require_once '/Users/ehalmkro/hive/Camagru/src/models/Core.class.php';

class commentModel
{
    private $pdo;
    private $dataArray;

    public function __construct()
    {
        $core = Core::getInstance();
        $this->pdo = $core->pdo;
    }

    public function addComment($iid, $uid, $username, $comment) // TODO: add comment email and toggle for notifications
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO 
                                comments (iid, uid, username, text, islike) 
                                VALUES (?, ?, ?, ?, FALSE)');
            $stmt->execute([$iid, $uid, $username, $comment]);
        } catch (PDOException $e) {
            echo "Error: ." . $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }

    public function removeComment($cid, $uid)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM 
                                comments WHERE cid=? AND uid=? AND islike=FALSE');
            $stmt->execute([$cid, $uid]);
        } catch (PDOException $e) {
            echo "Error: ." . $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }

    public function getComments($iid)
    {
        try {
            if (empty($iid))
                $stmt = $this->pdo->prepare('SELECT * FROM comments WHERE islike =FALSE ORDER BY cid DESC');
            else
                $stmt = $this->pdo->prepare('SELECT * FROM comments WHERE iid=? AND islike=FALSE ORDER BY cid DESC');
            empty($iid) ? $stmt->execute() : $stmt->execute([$iid]);
            $this->dataArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo "Error ." . $e->getMessage();
            return FALSE;
        }
        return $this->dataArray;
    }

    public function getLikes($iid)
    {
        try {
            if (empty($iid))
                $stmt = $this->pdo->prepare('SELECT * FROM comments WHERE islike=TRUE');
            else
                $stmt = $this->pdo->prepare('SELECT * FROM comments WHERE iid=? AND islike=TRUE');
            empty($iid) ? $stmt->execute() : $stmt->execute([$iid]);
            $this->dataArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
        } catch (PDOException $e) {
            echo "Error ." . $e->getMessage();
            return FALSE;
        }
        return $this->dataArray;
    }

    public function addLike($iid, $uid, $username)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM comments WHERE iid=? AND uid=? and islike=TRUE');
            $stmt->execute([$iid, $uid]);
            if ($stmt->fetch()) {
                echo "Already liked yo";
                return FALSE;
            }
            $stmt = $this->pdo->prepare('INSERT INTO 
                                comments (iid, uid, username, text, islike) 
                                VALUES (?, ?, ?, NULL, TRUE)');
            $stmt->execute([$iid, $uid, $username]);
        } catch (PDOException $e) {
            echo "Error: ." . $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }

    public function alreadyLiked($iid, $uid)
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM comments WHERE iid=? AND uid=? and islike=TRUE');
            $stmt->execute([$iid, $uid]);
            if ($stmt->fetch()) {
                return TRUE;
            }
        } catch (PDOException $e) {
            echo "Error: ." . $e->getMessage();
            return FALSE;
        }
        return FALSE;
    }

    public function removeLike($iid, $uid)
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM 
                                comments WHERE iid=? AND uid=? AND islike=TRUE');
            $stmt->execute([$iid, $uid]);
        } catch (PDOException $e) {
            echo "Error: ." . $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }

}