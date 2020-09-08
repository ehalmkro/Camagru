<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
Log in
<form action="/userController/login" method="POST">
    <input type="text" name="username" placeholder="enter username" required>
    <input type="password" name="password" placeholder="enter password" required>
    <button type="submit" name="loginbtn">Log in</button>
</form>
</html>