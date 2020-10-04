<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/src/controllers/userController.php';

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
<body>
<div class="container col-md bg-light border p-4 m-3 rounded">
    User settings:
    <!--suppress HtmlUnknownTarget, HtmlUnknownTarget -->
    <form action="/userController/changeUsername" method="POST">
        Change username:<br>
        <input type="text" name="newUsername" autocomplete="username" value="<? echo $userController->returnUserName($_SESSION['uid']) ?>">
        <input type="password" name="password" autocomplete="current-password" placeholder="Insert password"/>
        <button class="btn-sm btn-primary" type="submit" name="submit">Change username</button>
    </form>
    <!--suppress HtmlUnknownTarget, HtmlUnknownTarget -->
    <form action="/userController/changeEmail" method="POST">
        Change email:<br>
        <input type="text" name="newEmail" autocomplete="email" value="<? echo($userController->getUserData($_SESSION['uid'])['email']) ?>">
        <input type="password" name="password" autocomplete="current-password" placeholder="Insert password"/>
        <button class="btn-sm btn-primary" type="submit" name="submit">Change email</button>
    </form>

    <!--suppress HtmlUnknownTarget, HtmlUnknownTarget -->
    <form action="/userController/changePassword" method="POST">
        Change password:<br>
        <input type="text" name="newUsername" autocomplete="username" style="display:none"/>
        <input type="password" name="oldPass" autocomplete="current-password" placeholder="Insert old password"/>
        <input type="password" name="newPass" autocomplete="new-password"placeholder="Insert new password"/>
        <input type="password" name="newPass2" autocomplete="new-password" placeholder="Insert new password again"/>
        <button class="btn-sm btn-primary" type="submit" name="submit">Change password</button>
    </form>

    <!--suppress HtmlUnknownTarget, HtmlUnknownTarget -->
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
</div>

<button  class="btn-sm btn-primary" type="button" onclick="window.close()">Close</button>

</body>
</HTML>
