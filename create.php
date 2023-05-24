<?php

$app = require "./core/app.php";

// Create new instance of user
$user = new User($app->db);

// Validate input data
$validation_error = $user->validate();
if ($validation_error) {
	header("HTTP/1.1 400 Bad Request");
	die($validation_error);
}

// Insert it to database with POST data
$user->insert(array(
	'name' => $_POST['name'],
	'email' => $_POST['email'],
	'phone_number' => $_POST['phone_number'],
	'city' => $_POST['city']
));
