<?php

/**
 * User model
 */
class User extends BaseModel{
	
	// Define neccessary constansts so we know from which table to load data
	const tableName = 'users';
	// ClassName constant is important for find and findOne static functions to work
	const className = 'User';
	
	// Create getter functions
	
	public function getName() {
		return $this->getField('name');
	}
	
	public function getEmail() {
		return $this->getField('email');
	}

	public function getPhoneNumber() {
		return $this->getField('phone_number');
	}
	
	public function getCity() {
		return $this->getField('city');
	}

	public function validate() {
		// Validate required fields
		if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['city']))
			return 'Missing required data. Required fields are: name, email, city.';

		// Validate email
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
			return 'Invalid email format';

		// Validate phone number
		if (!preg_match('/^\+?[0-9() -]+$/', $_POST['phone_number']))
			return 'Invalid phone number format. Valid numbers contain only digits, spaces, parentheses, dashes and can start with a plus sign.';
	}
}