<?php
include_once 'db_connector.php';

    $dataB = [
        "error"
        
    ];
    $user_id = $_GET["user_id"];
    $sql = "SELECT * FROM user_pro_skills WHERE user_id = '" . $user_id . "'";
    //echo var_dump($sql);
    $result = $mysqli->query($sql);

    if ($result) {
        // output data of each row
        header('Content-Type: application/json');
        while($i = $result->fetch_assoc()){
            $data[] = $i;
        }
        echo json_encode($data);
    } else{
        echo json_encode($dataB);
    }
?>
