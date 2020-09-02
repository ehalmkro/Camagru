<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/Core.class.php';

class userModel
{
    private $pdo;

    public function __construct()
    {
        // session_start();
        $core = Core::getInstance();
        $this->pdo = $core->pdo;
        //    $this->tableName = "users";
        //    $this->fieldList = array('uid', 'username', 'password', 'email');
        //    $this->fieldList['uid'] = array('pkey' => 'y');
        //    $this->pkey = 'uid';
    }

    public function signUp($username, $password, $email)
    {
        $stmt = $this->pdo->prepare("SELECT username FROM users WHERE username LIKE ?;");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            echo "Username exists" . PHP_EOL;
            return FALSE;
        }
        if ($this->checkPasswordRequirements($password) == FALSE) {
            echo "Wrong password format" . PHP_EOL;
            return FALSE;
        }
        if ($this->checkEmailValidity($email) == FALSE) {
            echo "Wrong email format" . PHP_EOL;
            return FALSE;
        }
        $confirmationCode= bin2hex(random_bytes(32));
        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO 
									users (username, password, email, confirmationCode) 
									VALUES (:username, :password, :email, :confirmationCode);");

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':confirmationCode', $confirmationCode);
        $stmt->execute(); //TODO: ADD TRY
        //echo "Added user " . $username . PHP_EOL;
        return (TRUE);
    }

    public function auth($username, $password)
    {
        $stmt = $this->pdo->prepare("SELECT password, uid
                            FROM users WHERE username=?");
        $stmt->execute([$username]);
        if ($hash = $stmt->fetch()) {
            if (password_verify($password, $hash['password'])) {
                return $hash['uid'];
            }
        }
        return false;
    }

    public function login($username, $password)
    {
        $uid = $this->auth($username, $password);
        if ($uid === FALSE)
            return FALSE;
        return $uid;
    }

    public function logout()
    {
        unset($_SESSION['uid']);
        return TRUE;
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION['uid']) && $_SESSION['uid'])
            return $_SESSION['uid'];
        return FALSE;
    }

    public function getUsername($uid)
    {
        $stmt = $this->pdo->prepare("SELECT username FROM users WHERE uid=?");
        $stmt->execute([$uid]);
        if ($username = $stmt->fetch())
            return $username['username'];
        return FALSE;
    }

    public function getUid($username)
    {
        $stmt = $this->pdo->prepare("SELECT uid FROM users WHERE username=?");
        $stmt->execute([$username]);
        if ($uid = $stmt->fetch())
            return $uid['uid'];
        return FALSE;
    }

    public function getUserdata($uid)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE uid=?");
        $stmt->execute([$uid]);
        if ($dataArray = $stmt->fetchAll(PDO::FETCH_ASSOC))
            return $dataArray;
        return FALSE;
    }

    public function changeLoginDetail($uid, $newValue, $columnName)
    {
        echo "UPDATE users SET $columnName = $newValue WHERE uid = $uid";
        try {
            $stmt = $this->pdo->prepare("UPDATE users SET $columnName = ? WHERE uid = ?");
            $stmt->execute([$newValue, $uid]);
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage();
            $this->errors[] = $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }

    public function changePassword($uid, $newPass, $oldPass)
    {
        if (!$this->auth($this->getUsername($uid), $oldPass)) {
            echo "Wrong password" . PHP_EOL;
            return FALSE;
        }
        if (!$this->checkPasswordRequirements($newPass)) {
            echo "New password doesn't match requirements" . PHP_EOL;
            return FALSE;
        }
        $hash = password_hash($newPass, PASSWORD_DEFAULT);
        $this->changeLoginDetail($uid, $hash, "password");
        return TRUE;
    }

    public function changeUserName($uid, $password, $newUserName)
    {
        if (!$this->auth($this->getUsername($uid), $password)) {
            echo "Wrong password" . PHP_EOL;
            return FALSE;
        }
        $this->changeLoginDetail($uid, $newUserName, "username");
        return TRUE;
    }

    public function changeEmail($uid, $password, $newEmail)
    {
        if (!$this->auth($this->getUsername($uid), $password)) {
            echo "Wrong password" . PHP_EOL;
            return FALSE;
        }
        if (!$this->checkEmailValidity($newEmail)) {
            echo "Wrong password" . PHP_EOL;
            return FALSE;
        }
        $this->changeLoginDetail($uid, $newEmail, "email");
        return TRUE;
    }

    private function checkPasswordRequirements($password)
    {
        if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password))
            return TRUE;
        return FALSE;
    }

    private function checkEmailValidity($email)
    {
        if (preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $email))
            return TRUE;
        return FALSE;
    }

}