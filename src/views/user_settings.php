<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/userController.php';
//  include $_SERVER['DOCUMENT_ROOT'] . '/src/views/footer.php';

$userController = new userController();
$error = $_GET['error'] ? TRUE : FALSE;
if ($error) echo "Error!";
?>
<HTML>
<HEAD>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/style.css">
    <title>camagru || ehalmkro</title>
    <script>
        function changeNotificationStatus() {
            let notificationPreference;
            const button = document.getElementById("notificationCheck");
            if (!button.checked)
                notificationPreference = 0;
            else
                notificationPreference = 1;
            let data = new FormData();
            data.append("notificationPreference", notificationPreference);
            fetch('/index.php/userController/changeNotificationPreference', {
                method: "POST",
                mode: "same-origin",
                credentials: "same-origin",
                body: data
            }).then(response => response.json()
            ).then(
                success => console.log(success)
            ).catch(
                error => console.log(error)
            );
        }
    </script>
</HEAD>


<form action="/userController/changeUsername" method="POST">
    Change username:<br>
    <input type="text" name="newUsername" value="<? echo $userController->returnUserName($_SESSION['uid']) ?>">
    <input type="password" name="password" placeholder="Insert password"/>
    <button type="submit" name="submit">Change username</button>
</form>
<form action="/userController/changeEmail" method="POST">
    Change email:<br>
    <input type="text" name="newEmail" value="<? echo($userController->getUserData($_SESSION['uid'])['email']) ?>">
    <input type="password" name="password" placeholder="Insert password"/>
    <button type="submit" name="submit">Change email</button>
</form>

<form action="/userController/changePassword" method="POST">
    Change password:<br>
    <input type="password" name="oldPassword" placeholder="Insert old password"/>
    <input type="password" name="newPassword" placeholder="Insert new password"/>
    <button type="submit" name="submit">Change password</button>
</form>

<form action="/userController/changeNotificationPreference" method="POST">
    Allow email notifications<br>
    <label class="switch">
        <input id="notificationCheck" type="checkbox" onchange="changeNotificationStatus();"
            <? if (($userController->getUserData($_SESSION['uid'])['sendNotifications']) == 1): ?>
                checked="checked"
            <? endif; ?>
        >
        <span class="slider round">
        </span>
    </label>
</form>

<button type="button" onclick="window.close()">Close</button>

</body>
</HTML>
