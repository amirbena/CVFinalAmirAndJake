<?php
include_once 'connectionToDB.php';

$sql = "SELECT * FROM users";

$result = $mysqli->query($sql);

$users = [];
if ($result) {
    // output data of each row
    header('Content-Type: application/json');
    $data = [];
    while($user = $result->fetch_assoc()){
        array_push($data, $user);
    }
    echo json_encode($data);
} else{
    echo json_encode($dataB);
}

?>
