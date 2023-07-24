<?php

// If cookie is found for the token
if (isset($_COOKIE['account_access_key'])) {

	$account_access_key = urldecode($_COOKIE['account_access_key']);
	$result = verify_by_key($account_access_key); // If the access key is valid, $result will be the username, otherwise, it is false
	
	if ($result == false) { // key not valid
  		header("Location: /logout");
    	exit();

  	} elseif ($result == true) {

  		// Do nothing since the key is valid


  	} else { // not true nor false
		header("Location: /logout");
		exit();

	}
} else {  // key not set
	header("Location: /logout");
	exit();
}


function verify_by_key($account_access_key) {

	$token = verify_token_exp($account_access_key);  // returns decoded token, or null of time is invalid

	if ($token == null) {
		return false; // Token has expired or other wise is invalid by itself
	} else {

		$con = init_db_connection("mydatabase");

		// use the token to find account details
		if ($stmt = $con->prepare('SELECT id, fullname, email, username FROM accounts WHERE account_access_key = ?')) {
			$stmt->bind_param('s', $account_access_key);
			$stmt->execute();
			$stmt->store_result();

			if ($stmt->num_rows > 0) {
				$stmt->bind_result($id, $fullname, $email, $username);
				$stmt->fetch();

				$name = explode("|", $fullname);

				$_SESSION['id'] = $id;
				$_SESSION['fullname'] = $fullname;
				$_SESSION['firstname'] = $name[0];
				$_SESSION['lastname'] = $name[1];
				$_SESSION['email'] = $email;
				$_SESSION['username'] = $username;

				return true;

			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}