<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
Change password
<!--suppress HtmlUnknownTarget, HtmlUnknownTarget -->
<form action="/userController/resetPassword" method="POST">
    <input type="password" name="newPass" placeholder="enter new password" required>
    <input type="password" name="newPass2" placeholder="enter new password" required>
    <button type="submit" name="passwordbtn">Log in</button>
</form>
</html>