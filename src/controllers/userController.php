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

    public function getUserData($uid)
    {
        return $this->model->getUserdata($uid);
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
            $this->renewConfirmationCode($userdata['confirmationCode']);
            $this->logout();
        } else
            echo "Error!";
    }

    public function verifyAccount()
    {
        if (!isset($_GET['confirmationCode']) || !$this->model->verifyAccount($_GET['confirmationCode']))
            echo "Error!";
        else {
            $this->model->verifyAccount($_GET['confirmationCode']);
            $this->redirect("/index.php?status=accountVerified");
        }
    }

    private function renewConfirmationCode($oldConfirmationCode)
    {
        return $this->model->renewConfirmationCode($oldConfirmationCode);
    }

    public function signUp()
    {
        if (isset($_POST['signupbtn'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            if ($this->model->signUp($username, $password, $email)) {
                $user = $this->model->auth($username, $password);
                $userData = $this->getUserData($user);
                mail($userData['email'],
                    "Verify your account",
                    "Welcome to C A M A G R U, verify your account at: " . "http://" .
                    $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] .
                    "/userController/verifyAccount?confirmationCode=" . $userData['confirmationCode']);
                $this->redirect('/index.php?status=mailSent');
            }
        }

    }

    public function changeUsername()
    {
        if (isset($_SESSION['uid']) && isset($_POST['newUsername']))
            if ($this->model->changeUserName($_SESSION['uid'], $_POST['password'], $_POST['newUsername']))
                $this->redirect("/index.php/userSettings");
            else
                echo "Error!";
    }

    public function changeEmail()
    {
        if (isset($_SESSION['uid']) && isset($_POST['newEmail'])) {
            if ($this->model->changeEmail($_SESSION['uid'], $_POST['password'], $_POST['newEmail']))
                $this->redirect("/index.php/userSettings");
            else
                echo "Error!";

        }
    }

// TODO: change password

    public function login()
    {
        if (isset($_SESSION['uid']))
            include $_SERVER['DOCUMENT_ROOT'] . '/src/views/user_settings.php';
        else if (isset($_POST['loginbtn'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $uid = $this->model->login($username, $password);
            if ($uid != FALSE) {
                $_SESSION['uid'] = $uid;
                $this->redirect('/index.php');
            }
            else
                $this->redirect('/index.php?status=loginFailed');
        }
    }

    public function changeNotificationPreference()
    {
        if (!$this->model->changeNotificationPreference($_SESSION['uid'], $_POST['notificationPreference']))
            echo json_encode(array(
                "status" => "fail",
                "error" => true,
                "message" => "Couldn't change notification preference"));
        else
            echo json_encode(array(
                "status" => "success",
                "error" => false,
                "message" => "Changed preference"));;
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