<?php







/*
 * @package Validation
 * @author Harry Solovay
 * @link github.com/harrysolovay/... // replace
 * @version 0.1 1/15/2014
 */
	
	
	
	
	
	
	
// validation messages to be displayed to user
$validation_messages = array(
	
	'full_name' => array(
		0 => 'Enter your name in the name field.',
		1 => 'Enter a name that is less than 40 characters in length and consists only of letters and spaces.',
		2 => 'Enter a name that is less than 40 characters in length.',
		3 => 'Enter a name that consists only of letters and spaces.'
	),
		
	'first_name' => array(
		0 => 'Enter your first name in the first-name field.',
		1 => 'Enter a first name that is less than 20 characters in length and consists only of letters.',
		2 => 'Enter a first name that is less than 20 characters in length.',
		3 => 'Enter a first name that consists only of letters.'
	),
		
	'last_name' => array(
		0 => 'Enter your last name in the last-name field.',
		1 => 'Enter a last name that is less than 20 characters in length and consists only of letters.',
		2 => 'Enter a last name that is less than 20 characters in length.',
		3 => 'Enter a last name that consists only of letters.'
	),
		
	'username' => array(
		0 => 'Enter your desired username in username field.',
		1 => 'Enter a username that consists only of alphanumeric characters and is less than 20 characters in length.',
		2 => 'Enter a username that is less than 20 characters in length.',
		3 => 'Enter a username that consists only of alphanumeric characters.',
		4 => 'Enter a username that isn\'t yet registered with us.'
	),
		
	'email' => array(
		0 => 'Enter your email address in the email field.',
		1 => 'Enter a valid email address.',
		2 => 'Enter an email address that isn\'t yet registered with us.'
	),
		
	'phone_number' => array(
		0 => 'Enter your digits in the phone number field.',
		1 => 'Enter a valid phone number.',
		2 => 'Enter a phone number that isn\'t yet registered with us.'
	),
		
	'password' => array(
		0 => 'Enter your desired password in both password fields.',
		1 => 'Enter the same password in both password fields (must consist only of alphanumeric characters and be less than 20 characters in length).',
		2 => 'Enter the same password in both password fields (must be less than 20 characters in length).',
		3 => 'Enter the same password in both password fields (must consist only of alphanumeric characters).',
		4 => 'Enter a password that consists only of alphanumeric characters and is less than 20 characters in length.',
		5 => 'Enter the same password in both password fields',
		6 => 'Enter a password that is less than 20 characters in length.',
		7 => 'Enter a password that consists only of alphanumeric characters.'
	),
		
	'inquiry' => array(
		0 => 'Enter your message in the message field.',
		1 => 'Enter a message that is less than 500 characters in length.'
	)
		
);
	
	
	
	
	
	
	
/* === returns true if $var exists === */
function has_presence($var) {
	return !empty($var);
}
	
	
	
/* === returns true if $string is $length characters in length === */
function is_length($string, $length) {
	return strlen($string) == $length;
}
	
/* === returns true if $string is longer than $min_length === */
function is_longer_than($string, $min_length) {
	return strlen($string) > $min_length;
}
	
/* === returns true if $string is shorter than $min_length === */
function is_shorter_than($string, $min_length) {
	return strlen($string) < $min_length;
}
	
	
	
/* === returns true if $string consists only of alphabetic characters === */
function has_only_letters($string) {
	return ctype_alpha($string);
}
	
/* === returns true if $string consists only of alphabetic characters and spaces === */
function has_only_letters_and_spaces($string) {
	return preg_match("/^[a-zA-Z ]*$/", $string);
}
	
/* === returns true if $string consists only of numbers === */
function has_only_numbers($string) {
	return ctype_digit($string);
}
	
/* === returns true if $string consists only of alphabetic characters and numbers === */
function has_only_letters_and_numbers($string) {
	return ctype_alnum($string);
}
	
	
	
/* === returns true if $string_a is the same as $string_b === */
function strings_match($string_a, $string_b) {
	return $string_a == $string_b;
}
	
	
	
/* === returns true if $email follows normal email composition and has a DNS record === */
function is_real_email($email) {
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		return false;
	list($user, $domain) = explode('@', $email);
	if(!checkdnsrr($domain, 'MX'))
   		return false;
   	return true;
}
	
/* === returns true if $phone_number consists only of numbers and is less than 10 characters in length === */
function is_real_phone_number($phone_number) {
	return (is_length($phone_number, 10) || is_length($phone_number, 11)) && has_only_numbers($phone_number);
}
	
/* === returns true if the table $table has any column $key with a field containing the value $value === */
function is_in_db($table, $key, $value) {
	require_once('db_connection.php'); // replace
	$query = "SELECT * FROM {$table} WHERE {$key} = '{$value}';";
	$result = $connection -> query($query);
	return ($result -> num_rows) > 0;
}







/* === pushes $string to $_SESSION['validation_messages'] === */
function create_validation_message($validation_message) {
	if(!isset($_SESSION['validation_messages'])) {
		$_SESSION['validation_messages'] = array();
	} else {
		array_push($_SESSION['validation_messages'], $validation_message);
	}
}
	
/* === prints all messages from $_SESSION['validation_message'] === */
function print_validation_messages() {
	foreach($_SESSION['validation_messages'] as &$message) {
		echo "<span class='block'>" . $message . "</span>";
	}
}
	
/* === resets $_SESSION['validation_messages'] to an empty array === */
function clear_validation_messages() {
	$_SESSION['validation_messages'] = array();
}

/* === prints an instruction to fix form then calls of print_validation_messages() and clear_validation_messages() === */
function print_then_clear_all_validation_messages() {
	if(!empty($_SESSION['validation_messages'])) {
		echo "<span class='pre-block block'>Please correct the following:<span>";
		print_validation_messages();
		clear_validation_messages();
	}
}
	
	
	
	
	
	

function is_valid_full_name($full_name) {
	global $validation_messages;
	if(!has_presence($full_name)) {
		create_validation_message($validation_messages['full_name'][0]);
		return false;
	} else {
		$length_problem = is_longer_than($full_name, 40);
		$content_problem = !has_only_letters_and_spaces($full_name);
		if($length_problem && $content_problem) {
			create_validation_message($validation_messages['full_name'][1]);
			return false;
		} elseif($length_problem) {
			create_validation_message($validation_messages['full_name'][2]);
			return false;
		} elseif($content_problem) {
			create_validation_message($validation_messages['full_name'][3]);
			return false;
		}
	}
	return true;
}



function is_valid_name($which, $name) {
	global $validation_messages;
	if(!has_presence($name)) {
		create_validation_message($validation_messages[$which][0]);
		return false;
	} else {
		$length_problem = is_longer_than($name, 40);
		$content_problem = !has_only_letters($name);
		if($length_problem && $content_problem) {
			create_validation_message($validation_messages[$which][1]);
			return false;
		} elseif($length_problem) {
			create_validation_message($validation_messages[$which][2]);
			return false;
		} elseif($content_problem) {
			create_validation_message($validation_messages[$which][3]);
			return false;
		}
	}
	return true;
}



function is_valid_username($username, $can_be_in_db = true) {
	global $validation_messages;
	if(!has_presence($username)) {
		create_validation_message($validation_messages['username'][0]);
		return false;
	} else {
		$length_problem = is_longer_than($username, 40);
		$content_problem = !has_only_letters($username);
		if($length_problem && $content_problem) {
			create_validation_message($validation_messages['username'][1]);
			return false;
		} elseif($length_problem) {
			create_validation_message($validation_messages['username'][2]);
			return false;
		} elseif($content_problem) {
			create_validation_message($validation_messages['username'][3]);
			return false;
		} elseif(!$can_be_in_db && is_in_db('users', 'username', $username)) {
			create_validation_message($validation_messages['username'][4]);
			return false;
		}
	}
	return true;
}



function is_valid_email($email, $can_be_in_db = true) {
	global $validation_messages;
	if(!has_presence($email)) {
		create_validation_message($validation_messages['email'][0]);
		return false;
	} elseif(!is_real_email($email)) {
		create_validation_message($validation_messages['email'][1]);
		return false;
	} elseif(!$can_be_in_db && is_in_db('users', 'email', $email)) {
		create_validation_message($validation_messages['email'][2]);
		return false;
	}
	return true;
}



function is_valid_phone_number($phone_number, $can_be_in_db = true) {
	global $validation_messages;
	if(!has_presence($phone_number)) {
		create_validation_message($validation_messages['phone_number'][0]);
		return false;
	} elseif(!is_real_phone_number($phone_number)) {
		create_validation_message($validation_messages['phone_number'][1]);
		return false;
	} elseif(!$can_be_in_db && is_in_db('users', 'phone_number', $phone_number)) {
		create_validation_message($validation_messages['phone_number'][2]);
		return false;
	}
	return true;
}



function is_valid_password($password, $password_again) {
	global $validation_messages;
	if(!has_presence($password) && !has_presence($password_again)) {
		create_validation_message($validation_messages['password'][0]);
		return false;
	} else {
		$match_problem = !strings_match($password, $password_again);
		$length_problem = is_longer_than($password, 20);
		$content_problem = !has_only_letters_and_numbers($password);
		if($match_problem && $length_problem && $content_problem) {
			create_validation_message($validation_messages['password'][1]);
			return false;
		} elseif($match_problem && $length_problem) {
			create_validation_message($validation_messages['password'][2]);
			return false;
		} elseif($match_problem && $content_problem) {
			create_validation_message($validation_messages['password'][3]);
			return false;
		} elseif($length_problem && $content_problem) {
			create_validation_message($validation_messages['password'][4]);
			return false;
		} elseif($match_problem) {
			create_validation_message($validation_messages['password'][5]);
			return false;
		} elseif($length_problem) {
			create_validation_message($validation_messages['password'][6]);
			return false;
		} elseif($content_problem) {
			create_validation_message($validation_messages['password'][7]);
			return false;
		}
	}
	return true;
}



function is_valid_inquiry($inquiry) {
	global $validation_messages;
	if(!has_presence($inquiry)) {
		create_validation_message($validation_messages['inquiry'][0]);
		return false;
	} elseif(is_shorter_than($inquiry, 500)) {
		create_validation_message($validation_messages['inquiry'][1]);
		return false;
	}
	return true;
}







?>
