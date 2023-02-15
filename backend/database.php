<?php
/***
Test the data if it's a valid submission by checking the submission ID.
***/
if (!isset($_POST['submission_id'])) {
	die("Invalid submission data!");
}
/***
## Database Config

NOTE: 
Replace the values below with your MYSQL database environment variables 
to create a valid connection.
***/
$db_host = "sql.freedb.tech:3306";
$db_username = "freedb_Vortex_Ole";
$db_password = "!cG4Mh7WuyQHn#c";
$db_name = "freedb_Website";
$db_table = "user";

// Taking all 5 values from the form data(input)

/***
Connect to database.
***/
$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($mysqli->connect_error) {
	die("Connection failed: " . $mysqli->connect_error);
}
/***
## Data to Save

Prepare the data to prevent possible SQL injection vulnerabilities to the database.

NOTE: Add the POST data to save in your database.
To view the submission as POST data, see: https://www.jotform.com/help/?p=607527
***/
$name = $mysqli->real_escape_string(implode(" ", $_POST['username']));
$email = $mysqli->real_escape_string($_POST['email']);
$password = $mysqli->real_escape_string($_POST['password']);

/***
Prepare the test to check if the submission already exists in your database.
***/
$sid = $mysqli->real_escape_string($_POST['submission_id']);
$result = $mysqli->query("SELECT * FROM $db_table WHERE submission_id = '$sid'");


if ($result->num_rows > 0) {
	/* UPDATE query */
	$result = $mysqli->query("UPDATE $db_table 
		SET name = '$name',
			email = '$email', 
			message = '$message' 
		
		WHERE submission_id = '$sid'
	");
}
else {
	/* INSERT query */
	$result = $mysqli->query("INSERT IGNORE INTO $db_table (
		submission_id, 
		username,
		email, 
		password
	) VALUES (
		'$sid', 
		'$name', 
		'$email',
		'$password')
	");
}
/***
Display the outcome.
***/
if ($result === true) {
	echo "Success!";
}
else {
	echo "SQL error:" . $mysqli->error;
}

$mysqli->close();
?>
