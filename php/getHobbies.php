<?php
    include_once 'connectionToDB.php';

    $dataB = [
        "basicInfo" => [
            "name" => "",
        ]
    ];

    $sql = "SELECT * FROM hobbies;";
    //echo var_dump($sql);
    $result = $mysqli->query($sql);

    if ($result) {
        // output data of each row
        header('Content-Type: application/json');
        while ($a = $result->fetch_assoc()) {
            $data[] = $a;
        }
        echo json_encode($data);
    } else{
        echo json_encode($dataB);
    }
 ?>