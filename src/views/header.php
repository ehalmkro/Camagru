<?php session_start(); ?>

<html>
<head>
    <title>camagru || ehalmkro</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <script>
        function pop_up(url, w, h) {
            window.open(url, 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=no,width=' + w + ',height=' + h + ',directories=no,location=no')
        }
    </script>
</head>
<body>

<div class="col-md bg-secondary border rounded text-center">
    <img src="/public/img/resources/logo.png" width="30%" class="img-fluid mw-40"/>
    <div>
        <? if (isset($_SESSION['uid'])): { ?>

            <button class="btn-primary" onclick="pop_up('/index.php/takePicture', 900, 720)">Take picture w/ webcam
            </button>
            <button class="btn-primary" onclick="pop_up('/index.php/userSettings', 640, 480)">User settings</button>
            <form action="/userController/logout" class="text-right" method="POST">
                <button class="btn-dark text-right" type="submit" name="logoutbtn">Log out</button>
            </form>
        <? } else: { ?>
            <button class="btn-primary" onclick="pop_up('/index.php/login', 640, 480)">Log in / sign up / reset
                password
            </button>
        <? } endif; ?>
    </div>
</div>
</body>
</html>