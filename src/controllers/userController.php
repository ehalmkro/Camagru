<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/models/userModel.class.php';

class userController
{
    private $model;

    function __construct()
    {
        $this->model = new userModel();
    }

    public function handleRequest()
    {
        $op = trim($_SERVER['PATH_INFO'], '/'); //isset($_GET['op']) ? $_GET['op'] : NULL;
      //  print "requested stuff = " . $op . '<br/>' . PHP_EOL;
        switch ($op) {
            case 'logout':
                $this->logout();
                break;
            case 'login':
                $this->login();
                break;
            case 'signUp':
                $this->signUp();
                break;
            case NULL:
                if (isset($_SESSION['uid'])) {
                    $user = $this->model->getUsername($_SESSION['uid']);
                    include $_SERVER['DOCUMENT_ROOT'] . '/src/views/user_profile.php';
                } else
                    include $_SERVER['DOCUMENT_ROOT'] . '/src/views/homepage.php';
                break;
        }
    }

    public function signUp()
    {
        if (isset($_POST['signupbtn'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            if ($this->model->signUp($username, $password, $email)) {
                $user = $this->model->auth($username, $password);
                $_SESSION['uid'] = $user;
                include $_SERVER['DOCUMENT_ROOT'] . '/src/views/user_profile.php';
            }
        }

    }

    public function login()
    {
        if (isset($_SESSION['uid']))
            include $_SERVER['DOCUMENT_ROOT'] . '/src/views/user_profile.php';
        else if (isset($_POST['loginbtn'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $uid = $this->model->login($username, $password);
            if ($uid) {
                $_SESSION['uid'] = $uid;
                $user = $this->model->getUsername($uid);
                include $_SERVER['DOCUMENT_ROOT'] . '/src/views/user_profile.php';
            }
        } else
            echo "Wrong username / password" . PHP_EOL;
    }

    public function logout()
    {
        session_destroy();
        $this->redirect("index.php");
    }

    public function redirect($location)
    {
        header('Location:' . $location);
    }

}