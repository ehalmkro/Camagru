<?php
require_once 'Core.class.php';

class manageUser
{
    private $pdo;

    public function __construct()
    {
        // session_start();
        $core = Core::getInstance();
        $this->pdo = $core->pdo;
    }

    public function signUp($username, $password, $email)
    {
        $stmt = $this->pdo->prepare("SELECT username FROM users WHERE username LIKE ?;");
        $stmt->execute([$username]);

        // TODO: throw exceptions and add checks for username and email validity
        if ($stmt->fetch()) {
            echo "Username exists" . PHP_EOL;
            return FALSE;
        }
        if ($this->checkPasswordRequirements($password) == FALSE) {
            echo "Wrong password format" . PHP_EOL;
            return FALSE;
        }

        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO 
									users (username, password, email) 
									VALUES (:username, :password, :email);");

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        echo "Added user " . $username . PHP_EOL;
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
        if ($uid == false)
            return FALSE;
        $_SESSION['uid'] = $uid;
        return TRUE;
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
        return false;
    }


    private function checkPasswordRequirements($password)
    {
        if (preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password))
            return TRUE;
        return FALSE;
    }


}