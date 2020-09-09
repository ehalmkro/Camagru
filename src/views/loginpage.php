<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css"
          integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
</head>
<div class="container col-md bg-light border p-4 m-3 rounded">
    Log in
    <form action="/userController/login" method="POST">
        <input type="text" name="username" placeholder="enter username" required>
        <input type="password" name="password" placeholder="enter password" required>
        <button class="btn-sm btn-primary" type="submit" name="loginbtn">Log in</button>
    </form>
    Sign up
    <form action="/userController/signUp" method="POST">
        <input type="text" name="username" placeholder="enter username" required>
        <input type="password" name="password" placeholder="enter password" required>
        <input type="password" name="password2" placeholder="enter password again" required>
        <input type="email" name="email" placeholder="enter email" required>
        <button class="btn-sm btn-primary" type="submit" name="signupbtn">Sign up</button>
    </form>
    Reset password
    <form action="/userController/sendResetMail" method="POST">
        <input type="text" name="username" placeholder="enter username" required>
        <button class="btn-sm btn-primary" type="submit" name="resetbtn">Reset</button>
    </form>
</div>
</html>