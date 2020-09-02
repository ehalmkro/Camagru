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

    public function returnUserName($uid)
    {
        return $username = $this->model->getUserName($uid);
    }

    public function getSessionUid()
    {

        if (empty($_SESSION['uid']))
            echo json_encode(array(
                "status" => "failed to get session uid",
                "error" => true));
        else
            echo json_encode(array(
                "status" => "success",
                "error" => false,
                "uid" => $_SESSION['uid']));
        return;
    }

    public function getUserName()
    {

        if (empty($_POST['uid']))
            return;
        echo json_encode(array(
            "status" => "success",
            "error" => false,
            "username" => $this->model->getUsername($_POST['uid'])));
        return;

    }


    public function sendResetMail()
    {
        if (($userdata = $this->model->getUserdata($this->model->getUid($_POST['username']))[0]) === FALSE || !isset($_POST['resetbtn'])) {
            return;
        }
        echo $userdata['email'],
        "Password reset",
            "It seems you forgot your password. Reset it by clicking here " .
            "http://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . "/resetPassword/" . $userdata['uid'] . "/" .
            $userdata['confirmationCode'];

        mail($userdata['email'],
            "Password reset",
            "It seems you forgot your password. Reset it by clicking here " .
            $_SERVER['SERVER_PORT'] . "/resetPassword/" . $userdata['uid'] . "/" .
            $userdata['confirmationCode']);
    }

    public function resetPassword()
    {
        $userdata = $this->model->getUserdata($_SESSION['uid'])[0];
        if ($userdata['confirmationCode'] !== $_SESSION['confirmationCode'] || $_POST['newPass'] !== $_POST['newPass2']
            || !isset($_POST['passwordbtn'])) {
            echo "Error, try again!";
            return;
        }
        $password = password_hash($_POST['newPass'], PASSWORD_DEFAULT);
        if (($this->model->changeLoginDetail($userdata['uid'], $password,
                "password") === TRUE)) {
            $this->model->changeLoginDetail($userdata['uid'], bin2hex(random_bytes(32)), "confirmationCode"); //TODO: move to model?
            $this->logout();
        }
        else
            echo "Error!";
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
                // include $_SERVER['DOCUMENT_ROOT'] . '/src/views/user_profile.php';
                $this->redirect('/index.php');
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
                /*$user = $this->model->getUsername($uid);
                include $_SERVER['DOCUMENT_ROOT'] . '/src/views/user_profile.php';
            */
                $this->redirect('/index.php');
            }
        } else
            echo "Wrong username / password" . PHP_EOL;
    }

    public function logout()
    {
        session_destroy();
        $this->redirect("/index.php");
    }

    public function redirect($location)
    {
        header('Location:' . $location);
    }

}