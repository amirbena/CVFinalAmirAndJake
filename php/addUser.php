<?php
include_once 'db_connector.php';

if (isset($_POST) && !empty($_POST)){
	$validated = true;
	if (empty($_POST['first_name'])) {
		$errs[] = "First name is empty";
		$validated = false;
	}
	if (empty($_POST['last_name'])) {
		$errs[] = "Last name is empty";
		$validated = false;
	}
	if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['phone'])) {
		$errs[] = "Phone number should look like this 000-000-0000";
		$validated = false;
	}
	if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errs[] = "Email should be an email";
		$validated = false;
	}
	if (empty($_POST['area'])) {
		$errs[] = "Area is empty";
		$validated = false;
	}
	if (empty($_POST['about_me'])) {
		$errs[] = "Nothing is written about you";
		$validated = false;
	}
	if (empty($_POST['degree'])) {
		$errs[] = "Degree is empty";
		$validated = false;
	}
	if ($validated){
		// insert line to db

		$values = array($_POST['first_name'],
						$_POST['last_name'],
						$_POST['phone'],
						$_POST['email'],
						$_POST['area'],
						$_POST['about_me'],
						$_POST['degree']);
		$query = vsprintf('insert into users (first_name,last_name,phone,email,address,about_me,degree) values ("%s","%s","%s","%s","%s","%s","%s");',$values);

		$res = $mysqli->query($query);

		if ($res)
			echo "User Added<br/>";
		else {
            var_dump($query);
            die();
			echo "Error adding user<br/>";
        }
    }
	if (isset($errs)){
		foreach ($errs as $err){
			echo "$err <br/>";
		}
	}
}
?>
