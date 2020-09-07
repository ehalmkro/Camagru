<html>
<head>
    <title>camagru || ehalmkro</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/style.css">
    <script>
        function pop_up(url) {
            window.open(url, 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=640,directories=no,location=no')
        }
    </script>


</head>
<body>
<button onclick="pop_up('/index.php/takePicture')">Take picture w/ webcam</button>
<form action="/userController/logout" method="POST">
    <button type="submit" name="logoutbtn">Log out</button>
</form>
<button onclick="pop_up('/index.php/userSettings')">User settings</button>
</body>
</html>