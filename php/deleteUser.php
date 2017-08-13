<?php
    include_once 'connectionToDB.php';

    $sql_users = "DELETE FROM users WHERE id = '" . $_GET["user_id"] . "';";
    $sql_user_social_networks = "DELETE FROM user_social_networks WHERE user_id = '" . $_GET["user_id"] . "';";
    $sql_user_pro_skills = "DELETE FROM user_pro_skills WHERE user_id = '" . $_GET["user_id"] . "';";
    $sql_user_per_skills = "DELETE FROM user_per_skills WHERE user_id = '" . $_GET["user_id"] . "';";
    $sql_user_languages = "DELETE FROM user_languages WHERE user_id = '" . $_GET["user_id"] . "';";
    $sql_user_hobbies = "DELETE FROM user_hobbies WHERE user_id = '" . $_GET["user_id"] . "';";
    $sql_user_experience = "DELETE FROM user_experience WHERE user_id = '" . $_GET["user_id"] . "';";
    $sql_user_education = "DELETE FROM user_education WHERE user_id = '" . $_GET["user_id"] . "';";

    var_dump($sql_users);
    var_dump($sql_user_social_networks);
    var_dump($sql_user_pro_skills);
    var_dump($sql_user_per_skills);
    var_dump($sql_user_languages);
    var_dump($sql_user_hobbies);
    var_dump($sql_user_experience);
    var_dump($sql_user_education);

    $result = $mysqli->query($sql_users);
    $result = $mysqli->query($sql_user_social_networks);
    $result = $mysqli->query($sql_user_pro_skills);
    $result = $mysqli->query($sql_user_per_skills);
    $result = $mysqli->query($sql_user_languages);
    $result = $mysqli->query($sql_user_hobbies);
    $result = $mysqli->query($sql_user_experience);
    $result = $mysqli->query($sql_user_education);

    if ($result) {
        // output data of each row
        header('Content-Type: application/json');
        //$data = $result->fetch_assoc();
        //echo json_encode($data);
    } else{
        echo json_encode($dataB);
    }
?>
