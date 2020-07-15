<?php

	include('../config/database.php');
	include('isCli.php');

	if (isCli())
    {
		echo 'Add user' . PHP_EOL . 'Insert username:' . PHP_EOL ;
		$username = trim(fgets(STDIN));
		echo 'Set email for user ' . $username . PHP_EOL ;
		$email = trim(fgets(STDIN));
		echo 'Set password for user ' .  $username . PHP_EOL ;
		$password = trim(fgets(STDIN));
		echo 'Adding user ' . $username . ' with email ' . $email . PHP_EOL
	    .  'Are you sure? (y/n)' . PHP_EOL;
		if (trim(fgets(STDIN ))!= "y")
			exit(1);
    }
	else
	{
		$username = $_POST['login'];
		$password = $_POST['password'];
		$email = $_POST['email'];
	}
	$pdo = db_connect();
	$stmt = $pdo->prepare("SELECT username FROM users WHERE username LIKE ?;");
	$stmt->execute([$username]);
	if ($stmt->fetch())
	{
		echo "Username exists" . PHP_EOL;
		exit(1);
	}
	$password = password_hash($password, PASSWORD_DEFAULT);
	$stmt = $pdo->prepare("INSERT INTO 
									users (username, password, email) 
									VALUES (:username, :password, :email);");

	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':password', $password);
	$stmt->bindParam(':email', $email);
	$stmt->execute();
	echo "Added user " . $username . PHP_EOL;
	return (TRUE);