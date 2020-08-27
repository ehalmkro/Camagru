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

        if (empty($_POST['uid']))
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