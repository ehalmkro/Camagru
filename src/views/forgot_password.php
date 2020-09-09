<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
Reset password
<form action="/userController/sendResetMail" method="POST">
    <input type="text" name="username" placeholder="enter your username" required>
    <button type="submit" name="resetbtn">Send reset mail</button>
</form>
</html>