<?php

include('../config/database.php');
include('isCli.php');
include('../models/userModel.class.php');

echo 'Welcome to Camagru command line operations' . PHP_EOL
    . 'Choose your operation' . PHP_EOL
    . '1) Add user 2) Authenticate user 3) Get username from uid' . PHP_EOL;
$operation = trim(fgets(STDIN));
$newUser = new manageUser();

if ($operation != 3) {
    echo 'Insert username:' . PHP_EOL;
    $username = trim(fgets(STDIN));
    if ($operation === '1') {
        echo 'Set email for user or press enter ' . $username . PHP_EOL;
        $email = trim(fgets(STDIN));
    }
    echo 'Set password for user ' . $username . PHP_EOL;
    $password = trim(fgets(STDIN));
    /*		echo 'Are you sure? (y/n)' . PHP_EOL;
            if (trim(fgets(STDIN ))!= "y")
                exit(1);*/
} else {
    echo "Insert UID" . PHP_EOL;
    $uid = trim(fgets(STDIN));
    echo $newUser->getUsername($uid) . PHP_EOL;
}


switch ($operation) {
    case '1':
        $newUser->signUp($username, $password, $email);
        exit();
        break;
    case '2':
        $uid = $newUser->auth($username, $password);
        print 'Logged in as ' . $uid . PHP_EOL;
        break;
}