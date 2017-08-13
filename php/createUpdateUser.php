<?php
include_once 'connectionToDB.php';

if (isset($_POST) && !empty($_POST)){
	foreach ($_POST as $key => $input_arr) {
		$_POST[$key] = addslashes($input_arr);
	}
	$validated = true;
	$get_user_id_sql = "SELECT MAX(id) FROM users;";
	$result = $mysqli->query($get_user_id_sql);
	$user_id = $result->fetch_assoc();
	$user_id = $user_id['MAX(id)'];
	//this will be the id for the user
	$user_id++;

	// HEADER - PART 1
	if (empty($_POST['first_name'])) {
		$errs[] = "First name is empty";
		$validated = false;
	}
	if (empty($_POST['last_name'])) {
		$errs[] = "Last name is empty";
		$validated = false;
	}
	if (empty($_POST['degree'])) {
		$errs[] = "degree is empty";
		$validated = false;
	}
	if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['phone'])) {
		$errs[] = "Phone number should look like this 000-000-0000";
		$validated = false;
	}
	if (empty($_POST['address'])) {
		$errs[] = "adress is empty";
		$validated = false;
	}
	if (empty($_POST['email'])) {
		$errs[] = "email is empty";
		$validated = false;
	}
	elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$errs[] = "Email should be an email";
		$validated = false;
	}
	if (empty($_POST['about_me'])) {
		$errs[] = "Tell us somthing about yourself";
		$validated = false;
	}
	if($validated){
		$user_values = array(
            mysqli_real_escape_string($mysqli, $_POST['first_name']),
			mysqli_real_escape_string($mysqli, $_POST['last_name']),
			mysqli_real_escape_string($mysqli, $_POST['phone']),
			mysqli_real_escape_string($mysqli, $_POST['email']),
			mysqli_real_escape_string($mysqli, $_POST['address']),
			mysqli_real_escape_string($mysqli, $_POST['about_me']),
			mysqli_real_escape_string($mysqli, $_POST['degree'])
		);
		if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
			$user_values[] = mysqli_real_escape_string($mysqli, $_GET['edit_id']);
			$users_query = vsprintf('UPDATE users
				SET
				first_name="%s", last_name="%s", phone="%s", email="%s",
				address="%s", about_me="%s", degree="%s"
				WHERE
				id="%s"',$user_values);
		} else{
			$users_query = vsprintf('insert into users (first_name,last_name,phone,email,address,about_me,degree)
	        values ("%s","%s","%s","%s","%s","%s","%s");', $user_values);// insert to users table
		}
	}
	// SOCIAL NETWORKS - PART 2
	$social_networks = "SELECT * FROM social_networks;";
    $result = $mysqli->query($social_networks);
	while ($a = $result->fetch_assoc()) {
		$data[] = $a;
	}
	$networks_empty = true;
	$networks_first = array_search($data[0]['name'], array_keys($_POST));
	$networks_data = array_slice($_POST, $networks_first, sizeof($data));
	if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
		$sql_delete_old_data = "DELETE FROM user_social_networks WHERE user_id='" . $_GET['edit_id'] . "';";
		$result = mysqli_query($mysqli, $sql_delete_old_data);
	}
	foreach ($networks_data as $key => $value) {
		if(!empty($value)){
			$networks_empty = false;
			$networks_sql = "SELECT id FROM social_networks WHERE name='" . $key . "';";
			$result = $mysqli->query($networks_sql);
			$network_id = $result->fetch_assoc()['id'];
			if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
				$networks_values = array(
					$network_id,
					$_GET['edit_id'],
					$value
				);
				$networks_queries[] = vsprintf('insert into user_social_networks (network_id,user_id,value)
				values ("%s","%s","%s");', $networks_values);// insert to users table
			} else{
				$networks_values = array(
					$network_id,
					$user_id,
					$value
				);
				$networks_queries[] = vsprintf('insert into user_social_networks (network_id,user_id,value)
				values ("%s","%s","%s");', $networks_values);// insert to users table
			}
		}
	}
	if($networks_empty){
		$errs[] = 'Something is not right with the social networks section';
		$validated = false;
	}
	// EXPERIENCE - PART 3
	// finding the first per skill in the database
	// this way we will know where the experiences ends in the $_POST
	$sql_per_skills = "SELECT name FROM per_skills LIMIT 1;";
	$sql_pro_skills = "SELECT name FROM pro_skills LIMIT 1;";
	$result = $mysqli->query($sql_per_skills);
	unset($data);
	while ($a = $result->fetch_assoc()) {
		$data[] = $a;
	}
	$result = $mysqli->query($sql_pro_skills);
	while ($a = $result->fetch_assoc()) {
		$data[] = $a;
	}
	$first_per = array_search($data[0]['name'], array_keys($_POST));
	$first_pro = array_search($data[1]['name'], array_keys($_POST));
	$exp_empty = true;
	if($experience_first = array_search("exp_title_0", array_keys($_POST))){
		$experience_last = ($first_pro < $first_per ? $first_pro : $first_per);
		$experience_data = array_slice($_POST, $experience_first, $experience_last - $experience_first);
		//create a dictionary of the experience section
		$counter = 0;
		if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
			$sql_delete_old_data = "DELETE FROM user_experience WHERE user_id='" . $_GET['edit_id'] . "';";
			$result = mysqli_query($mysqli, $sql_delete_old_data);
		}
		foreach ($experience_data as $key => $value) { // for each key and value
			$counter++;
			if (!empty($value)) { // if the value is not empty
				$experience_values[] = $value; // add this value and his key to experience_values
				$exp_empty = false;
			}
			if($counter % 5 === 0){// when we got through 5 elements
				//we need to check if all the inputs was OK
				if(isset($experience_values)){
					if(sizeof($experience_values) % 5 == 0){// if the number of not values is also 5
						// it means that all the fields were full
						if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
							$values_exp = array(
					            mysqli_real_escape_string($mysqli, $_GET['edit_id']),
								mysqli_real_escape_string($mysqli, $experience_values[0]),
								mysqli_real_escape_string($mysqli, $experience_values[3]),
								mysqli_real_escape_string($mysqli, $experience_values[1]),
								mysqli_real_escape_string($mysqli, $experience_values[2]),
								mysqli_real_escape_string($mysqli, $experience_values[4])
							);
							$experience_queries[] = vsprintf('insert into user_experience (user_id,title,company,start_date,end_date,description)
							values ("%s","%s","%s","%s","%s","%s");', $values_exp);// insert to user_experience table
						} else{
							$values_exp = array(
								mysqli_real_escape_string($mysqli, $user_id),
								mysqli_real_escape_string($mysqli, $experience_values[0]),
								mysqli_real_escape_string($mysqli, $experience_values[3]),
								mysqli_real_escape_string($mysqli, $experience_values[1]),
								mysqli_real_escape_string($mysqli, $experience_values[2]),
								mysqli_real_escape_string($mysqli, $experience_values[4])
							);
							$experience_queries[] = vsprintf('insert into user_experience (user_id,title,company,start_date,end_date,description)
							values ("%s","%s","%s","%s","%s","%s");', $values_exp);// insert to user_experience table
						}
					}
				}
				else {
					$errs[] = "Something is not right with the experience section";
					$validated = false;
					$exp_empty = true;
					break;
				}
				$counter = 0;
				unset($experience_values);
			}
		}
	}
	else {
		$errs[] = "Tell at least about one experience";
		$validated = false;
	}
	// SKILLS - PART 4
	// get all the per and pro skills
	$sql_per_skills = "SELECT * FROM per_skills;";
	$sql_pro_skills = "SELECT * FROM pro_skills;";
	// commit the queries to the database
	$per_result = $mysqli->query($sql_per_skills);
	$pro_result = $mysqli->query($sql_pro_skills);
	// fetch the data from the database
	while ($a = $per_result->fetch_assoc()) {
		$per_skills[] = $a;
	}
	while ($a = $pro_result->fetch_assoc()) {
		$pro_skills[] = $a;
	}
	// get number of per and pro skills
	$per_len = sizeof($per_skills);
	$pro_len = sizeof($pro_skills);
	// get the data from $_POST
	$per_first = array_search($per_skills[0]['name'], array_keys($_POST));
	$per_data = array_slice($_POST, $per_first, $per_len);
	$pro_first = array_search($pro_skills[0]['name'], array_keys($_POST));
	$pro_data = array_slice($_POST, $pro_first, $pro_len);
	$per_empty = true;
	$pro_empty = true;
	$counter = 0;
	// check if all the data in per skills in correct
	if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
		$sql_delete_old_data = "DELETE FROM user_per_skills WHERE user_id='" . $_GET['edit_id'] . "';";
		$result = mysqli_query($mysqli, $sql_delete_old_data);
	}
	foreach ($per_data as $key => $value) {
		if(!empty($value)){
			if(!is_numeric($value)){
				$validated = false;
				$errs[] = "Somthing is not right with per skills";
				break;
			}
			else {
				if($value < 0 or $value > 100){
					$validated = false;
					$errs[] = "Somthing is not right with per skills";
					break;
				}
				else if($value != 0){
					if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
						$per_values = array(
							mysqli_real_escape_string($mysqli, $per_skills[$counter]['id']),
							mysqli_real_escape_string($mysqli, $_GET['edit_id']),
							mysqli_real_escape_string($mysqli, $value)
						);
						$per_queries[] = vsprintf('insert into user_per_skills (skill_id,user_id,value)
						values ("%s","%s","%s");', $per_values);// insert to user_per_skills table
					} else{
						$per_values = array(
							mysqli_real_escape_string($mysqli, $per_skills[$counter]['id']),
							mysqli_real_escape_string($mysqli, $user_id),
							mysqli_real_escape_string($mysqli, $value)
						);
						$per_queries[] = vsprintf('insert into user_per_skills (skill_id,user_id,value)
						values ("%s","%s","%s");', $per_values);// insert to user_per_skills table
					}
				}
			}
			$counter++;
		}
	}
	if(!isset($per_queries)){
		$validated = false;
		$errs[] = "Somthing is not right with per skills";
	}
	// check if all the data in pro skills in correct
	$counter = 0;
	if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
		$sql_delete_old_data = "DELETE FROM user_pro_skills WHERE user_id='" . $_GET['edit_id'] . "';";
		$result = mysqli_query($mysqli, $sql_delete_old_data);
	}
	foreach ($pro_data as $key => $value) {
		if(!empty($value)){
			if(!is_numeric($value)){
				$validated = false;
				$errs[] = "Somthing is not right with pro skills";
				break;
			}
			else {
				if($value < 0 or $value > 100){
					$validated = false;
					$errs[] = "Somthing is not right with pro skills";
					break;
				}
				else if($value != 0){
					if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
						$pro_values = array(
							mysqli_real_escape_string($mysqli, $pro_skills[$counter]['id']),
							mysqli_real_escape_string($mysqli, $_GET['edit_id']),
							mysqli_real_escape_string($mysqli, $value)
						);
						$pro_queries[] = vsprintf('insert into user_pro_skills (skill_id,user_id,value)
						values ("%s","%s","%s");', $pro_values);// insert to user_per_skills table
					} else{
						$pro_values = array(
							mysqli_real_escape_string($mysqli, $pro_skills[$counter]['id']),
							mysqli_real_escape_string($mysqli, $user_id),
							mysqli_real_escape_string($mysqli, $value)
						);
						$pro_queries[] = vsprintf('insert into user_pro_skills (skill_id,user_id,value)
						values ("%s","%s","%s");', $pro_values);// insert to user_pro_skills table
					}
				}
			}
			$counter++;
		}
	}
	if(!isset($pro_queries)){
		$validated = false;
		$errs[] = "Somthing is not right with pro skills";
	}
	// EDUCATION - PART 5
	$sql_hobbies = "SELECT name FROM hobbies;";
	$sql_languages = "SELECT name FROM languages;";
	$result = $mysqli->query($sql_hobbies);
	unset($data);
	// fetch hobbies from database
	while ($a = $result->fetch_assoc()) {
		$data[] = $a;
	}
	// find the first index of hobbies in $_POST
	foreach ($data as $key => $value) {
		if($hobbies_first = array_search($value['name'], array_keys($_POST))){
			break;
		}
	}
	$result = $mysqli->query($sql_languages);
	// fetch hobbies from database
	unset($data);
	while ($a = $result->fetch_assoc()) {
		$data[] = $a;
	}
	// find the first index of languages in $_POST
	foreach ($data as $value) {
		if($languages_first = array_search($value['name'], array_keys($_POST))){
			break;
		}
	}
	$edu_empty = true;
	$education_first = array_search("edu_title_0", array_keys($_POST));
	$education_last = (!empty($hobbies_first) ? $hobbies_first : $languages_first);
	//create a dictionary of the education section
}
	$education_data = array_slice($_POST, $education_first, $education_last - $education_first);
	$counter = 0;
	if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
		$sql_delete_old_data = "DELETE FROM user_education WHERE user_id='" . $_GET['edit_id'] . "';";
		$result = mysqli_query($mysqli, $sql_delete_old_data);
	}
	foreach ($education_data as $key => $value) { // for each key and value
		$counter++;
		if (!empty($value)) { // if the value is not empty
			$education_values[] = $value; // add this value and his key to education_values
			$edu_empty = false;
		}
		if($counter === 5){
			// when we got through 5 elements we need to check if all the inputs was OK
			if(isset($education_values)){
				if(sizeof($education_values) === 5){// if the number of not values is also 5
					// it means that all the fields were full
					if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
						$values_edu = array(
							$_GET['edit_id'],
							mysqli_real_escape_string($mysqli, $education_values[0]),//title
							mysqli_real_escape_string($mysqli, $education_values[3]),//location
							mysqli_real_escape_string($mysqli, $education_values[1]),//start
							mysqli_real_escape_string($mysqli, $education_values[2]),//end
							mysqli_real_escape_string($mysqli, $education_values[4])//description
						);
						$education_queries[] = vsprintf('insert into user_education (user_id,title,location,start_date,end_date,description)
						values ("%s","%s","%s","%s","%s","%s");', $values_edu);// insert to user_education table
					}
					$values_edu = array(
			            $user_id,
						mysqli_real_escape_string($mysqli, $education_values[0]),//title
						mysqli_real_escape_string($mysqli, $education_values[3]),//location
						mysqli_real_escape_string($mysqli, $education_values[1]),//start
						mysqli_real_escape_string($mysqli, $education_values[2]),//end
						mysqli_real_escape_string($mysqli, $education_values[4])//description
					);
					$education_queries[] = vsprintf('insert into user_education (user_id,title,location,start_date,end_date,description)
			        values ("%s","%s","%s","%s","%s","%s");', $values_edu);// insert to user_education table
				}
				else {
					$errs[] = "Something is wrong with the education";
					$validated = false;
					$edu_empty = true;
					break;
				}
			}
			else {
				$errs[] = "Something is wrong with the education";
				$validated = false;
				$edu_empty = true;
				break;
			}
			$counter = 0;
			unset($education_values);
		}
	}
	// HOBBIES - PART 6
	if(!empty($hobbies_first)){
		$hobbies_data = array_slice($_POST, $hobbies_first, $languages_first - $hobbies_first);
		if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
			$sql_delete_old_data = "DELETE FROM user_hobbies WHERE user_id='" . $_GET['edit_id'] . "';";
			$result = mysqli_query($mysqli, $sql_delete_old_data);
		}
		foreach ($hobbies_data as $key => $value) {
			$hobby_sql = "SELECT id FROM hobbies WHERE name='" . $key . "';";
			$result = $mysqli->query($hobby_sql);
			$hobby_id = $result->fetch_assoc()['id'];
			if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
				$hobbies_values = array(
					mysqli_real_escape_string($mysqli, $hobby_id),
					mysqli_real_escape_string($mysqli, $_GET['edit_id']),
					mysqli_real_escape_string($mysqli, $value)
				);
				$hobbies_queries[] = vsprintf('insert into user_hobbies (hobby_id,user_id,value)
				values ("%s","%s","%s");', $hobbies_values);// insert to user_hobbies table
			} else{
				$hobbies_values = array(
					mysqli_real_escape_string($mysqli, $hobby_id),
					mysqli_real_escape_string($mysqli, $user_id),
					mysqli_real_escape_string($mysqli, $value)
				);
				$hobbies_queries[] = vsprintf('insert into user_hobbies (hobby_id,user_id,value)
				values ("%s","%s","%s");', $hobbies_values);// insert to user_hobbies table
			}
		}
	}
	else{
		$validated = false;
		$errs[] = "please tell us about your hobbies";
	}
	// LANGUAGES - PART 7
	if(!empty($languages_first)){
		$languages_data = array_slice($_POST, $languages_first);
		$languages_error = false;
		if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
			$sql_delete_old_data = "DELETE FROM user_languages WHERE user_id='" . $_GET['edit_id'] . "';";
			$result = mysqli_query($mysqli, $sql_delete_old_data);
		}
		foreach ($languages_data as $key => $value) {
			if(is_numeric($value)){
				if($value > 0 and $value <= 100){
					$languages_sql = "SELECT id FROM languages WHERE name='" . $key . "';";
					$result = $mysqli->query($languages_sql);
					$language_id = $result->fetch_assoc()['id'];
					if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
						$languages_values = array(
							mysqli_real_escape_string($mysqli, $language_id),
							mysqli_real_escape_string($mysqli, $_GET['edit_id']),
							mysqli_real_escape_string($mysqli, $value)
						);
						$languages_queries[] = vsprintf('insert into user_languages (language_id,user_id,value)
						values ("%s","%s","%s");', $languages_values);// insert to user_languages table
						continue;
					} else{
						$languages_values = array(
							mysqli_real_escape_string($mysqli, $language_id),
							mysqli_real_escape_string($mysqli, $user_id),
							mysqli_real_escape_string($mysqli, $value)
						);
						$languages_queries[] = vsprintf('insert into user_languages (language_id,user_id,value)
						values ("%s","%s","%s");', $languages_values);// insert to user_languages table
						continue;
					}
				}
				else {
					$languages_error = true;
				}
			}
			else {
				if(!empty($value) and !is_numeric($value)){
					$languages_error = true;
				}
			}
			if ($languages_error) {
				$validated = false;
				$errs[] = "Somthing is wrong with the languages section";
				break;
			}
		}
		if(!isset($languages_queries)){
			if(!$languages_error){
				$validated = false;
				$errs[] = "Somthing is wrong with the languages section";
			}
		}
	}
	function delete_user($user_id){
		$dbParams = array (
		  'hostname' => 'localhost',
		  'username' => 'root',
		  'password' => '',
		  'database' => 'project'
		);
		$mysqli = new mysqli($dbParams['hostname'], $dbParams['username'], $dbParams['password'], $dbParams['database']);

		$sql_users = "DELETE FROM users WHERE id = '" . $user_id . "';";
	    $sql_user_social_networks = "DELETE FROM user_social_networks WHERE user_id = '" . $user_id . "';";
	    $sql_user_pro_skills = "DELETE FROM user_pro_skills WHERE user_id = '" . $user_id . "';";
	    $sql_user_per_skills = "DELETE FROM user_per_skills WHERE user_id = '" . $user_id . "';";
	    $sql_user_languages = "DELETE FROM user_languages WHERE user_id = '" . $user_id . "';";
	    $sql_user_hobbies = "DELETE FROM user_hobbies WHERE user_id = '" . $user_id . "';";
	    $sql_user_experience = "DELETE FROM user_experience WHERE user_id = '" . $user_id . "';";
	    $sql_user_education = "DELETE FROM user_education WHERE user_id = '" . $user_id . "';";

	    $result = $mysqli->query($sql_users);
	    $result = $mysqli->query($sql_user_social_networks);
	    $result = $mysqli->query($sql_user_pro_skills);
	    $result = $mysqli->query($sql_user_per_skills);
	    $result = $mysqli->query($sql_user_languages);
	    $result = $mysqli->query($sql_user_hobbies);
	    $result = $mysqli->query($sql_user_experience);
	    $result = $mysqli->query($sql_user_education);
	}
	$query_error = false;
	if($validated){
		/*
		IF IT IS AN EDIT SO WE NEED TO DELETE ALL THE OLD DATA FIRST

		if(isset($_GET['edit_id']) and !empty($_GET['edit_id'])){
			delete_user($_GET['edit_id']);
		}*/
		$mysqli->query($users_query);

		foreach ($networks_queries as $key => $value) {
			if($query_error == false){
				$result = $mysqli->query($value);
				if($result == false){
					$query_error = true;
					$errs[] = "error executing social networks query(<br>query:<br><br>" . $value . "<br><br>)";
					break;
				}
			}
		}
		foreach ($experience_queries as $key => $value) {
			if($query_error == false){
				$result = mysqli_query($mysqli, $value);
				if($result == false){
					$query_error = true;
					$errs[] = "error executing experience query:<br>Key: ". $key . "<br>size: ". strlen($value) . "<br>query:<br><br>" . $value . "<br><br>";
					break;
				}
			}
		}
		foreach ($per_queries as $key => $value) {
			if($query_error == false){
				$result = $mysqli->query($value);
				if($result == false){
					$query_error = true;
					$errs[] = "error executing per skill query(<br>query:<br><br>" . $value . "<br><br>)";
					break;
				}
			}
		}
		foreach ($pro_queries as $key => $value) {
			if($query_error == false){
				$result = $mysqli->query($value);
				if($result == false){
					$query_error = true;
					$errs[] = "error executing pro skill query(<br>query:<br><br>" . $value . "<br><br>)";
					break;
				}
			}
		}
		foreach ($education_queries as $key => $value) {
			if($query_error == false){
				$result = $mysqli->query($value);
				if($result == false){
					$query_error = true;
					$errs[] = "error executing education query(<br>query:<br><br>" . $value . "<br><br>)";
					break;
				}
			}
		}
		foreach ($hobbies_queries as $key => $value) {
			if($query_error == false){
				$result = $mysqli->query($value);
				if($result == false){
					$query_error = true;
					$errs[] = "error executing hobbies query(<br>query:<br><br>" . $value . "<br><br>)";
					break;
				}
			}
		}
		foreach ($languages_queries as $key => $value) {
			if($query_error == false){
				$result = $mysqli->query($value);
				if($result == false){
					$query_error = true;
					$errs[] = "error executing languages query(<br>query:<br><br>" . $value . "<br><br>)";
					break;
				}
			}
		}
	}
	else{
		echo "<div>";
		foreach ($errs as $value) {
			echo "->  " . $value . "<br>";
		}
		echo "</div><br>";
	}
	if($query_error){
		delete_user($user_id);
		/*
		$sql_users = "DELETE FROM users WHERE id = '" . $user_id . "';";
	    $sql_user_social_networks = "DELETE FROM user_social_networks WHERE user_id = '" . $user_id . "';";
	    $sql_user_pro_skills = "DELETE FROM user_pro_skills WHERE user_id = '" . $user_id . "';";
	    $sql_user_per_skills = "DELETE FROM user_per_skills WHERE user_id = '" . $user_id . "';";
	    $sql_user_languages = "DELETE FROM user_languages WHERE user_id = '" . $user_id . "';";
	    $sql_user_hobbies = "DELETE FROM user_hobbies WHERE user_id = '" . $user_id . "';";
	    $sql_user_experience = "DELETE FROM user_experience WHERE user_id = '" . $user_id . "';";
	    $sql_user_education = "DELETE FROM user_education WHERE user_id = '" . $user_id . "';";

	    $result = $mysqli->query($sql_users);
	    $result = $mysqli->query($sql_user_social_networks);
	    $result = $mysqli->query($sql_user_pro_skills);
	    $result = $mysqli->query($sql_user_per_skills);
	    $result = $mysqli->query($sql_user_languages);
	    $result = $mysqli->query($sql_user_hobbies);
	    $result = $mysqli->query($sql_user_experience);
	    $result = $mysqli->query($sql_user_education);
*/
		echo "<div>";
		foreach ($errs as $value) {
			echo " *  " . $value . "<br>";
		}
		echo "</div><br>";
	}
	$mysqli->close();
?>
