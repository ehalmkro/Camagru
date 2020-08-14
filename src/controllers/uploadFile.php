<?php
    session_start();

    //TODO: create IMAGE TABLE with image ids, userid, timestamp, likes
    // MOVE THIS TO A MODEL!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    $filename = uniqid("img_");
    $file = $_POST['file'];
    $file = str_replace(' ','+',$file);
    $file = base64_decode($file);
    file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/public/img/uploads/" . $filename . ".jpg", $file);

    $response = array(
        "status" => "success",
        "error" => false,
        "message" => trim($_SERVER['PATH_INFO'], '/'));
    echo json_encode($response);
