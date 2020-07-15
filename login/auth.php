<?php
    include('../config/database.php');
    include('isCli.php');

    if (isCli())
    {
        echo 'Insert username' . PHP_EOL;
        $username = trim(fgets(STDIN));
        echo 'Insert password' . PHP_EOL;
        $password = trim(fgets(STDIN));
    }
    else {
        $username = $_POST['username'];
        $password = $_POST['password'];
    }

    $pdo = db_connect();
    $stmt = $pdo->prepare("SELECT password 
                            FROM users WHERE username=?");
    $stmt->execute([$username]);
    if ($hash = $stmt->fetchColumn()) {
        if (password_verify($password, $hash)) {
            echo "OK" . PHP_EOL;
            return TRUE;
        }
    }
    echo "KO" . PHP_EOL;
    return FALSE;

