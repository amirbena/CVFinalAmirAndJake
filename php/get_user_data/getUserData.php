<?php
include_once 'db_connector.php';

    $dataB = [
        "error"
    ];
    $user_id = $_GET["user_id"];
    $sql = "SELECT * FROM users WHERE id = '" . $user_id . "'";
    //echo var_dump($sql);
    $result = $mysqli->query($sql);

    if ($result) {
        // output data of each row
        header('Content-Type: application/json');
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else{
        echo json_encode($dataB);
    }
?>
