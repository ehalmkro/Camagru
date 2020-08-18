<?php
include $_SERVER['DOCUMENT_ROOT'] . '/src/views/header.php';
?>

<html>
Log in
<form action="/userController/login" method="POST">
    <input type="text" name="username" placeholder="enter username" required>
    <input type="password" name="password" placeholder="enter password" required>
    <button type="submit" name="loginbtn">Log in</button>
</form>
Sign up
<form action="/userController/signUp" method="POST">
    <input type="text" name="username" placeholder="enter username" required>
    <input type="password" name="password" placeholder="enter password" required>
    <input type="password" name="password2" placeholder="enter password again" required>
    <input type="email" name="email" placeholder="enter email" required>
    <button type="submit" name="signupbtn">Sign up</button>
</form>
</html>