<?php

session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/../hidden/system_functions.php';

// if the user submitted a username and password
if (isset($_POST['username']) && isset($_POST['password'])) {
    $result = verify_by_password($_POST['username'], $_POST['password']);
    if ($result == true) {
		header("HTTP/1.1 201 Logged In");
    } else {
      	header("HTTP/1.1 403 Forbidden");
    }
    exit();
}



// If cookie is found for the token
if (isset($_COOKIE['account_access_key'])) {
	$account_access_key = urldecode($_COOKIE['account_access_key']);
	$result = verify_by_key($account_access_key); // If the access key is valid, $result will be the username, otherwise, it is false
	
	if ($result == true) {
  		header("Location: /home");
    	
  	} else {
  		$expire_time = time() - 3600;
  		setcookie('account_access_key', '', $expire_time, '', '');
  		header("Location: /");
	}
	exit();
}





// Takes a username and password to verify, returns false if no verify,
// if verify, sets cookie to the token if in db, if not in db, gen one, put in db, and give as cookie
function verify_by_password($username, $password) {

    $con = init_db_connection("mydatabase");

    // Use the token to find account details
    if ($stmt = $con->prepare('SELECT id, username, fullname, email, username, password, account_access_key FROM accounts WHERE username = ? OR email = ?')) {
        $stmt->bind_param('ss', $username, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $username, $fullname, $email, $db_username, $hashed_password, $token);
            $stmt->fetch();
            
            // the password is already base64 encoded 2 times at this point by the client
            if (password_verify($password, $hashed_password)) {

            	$name = explode("|", $fullname);

            	$_SESSION['id'] = $id;
				$_SESSION['firstname'] = $name[0];
				$_SESSION['lastname'] = $name[1];
				$_SESSION['email'] = $email;
				$_SESSION['username'] = $username;

				if ($token == null) {
    				$token = generate_token();
    				if ($stmt = $con->prepare('UPDATE accounts SET account_access_key=?  WHERE username = ?')) {
    				    $stmt->bind_param('ss', $token, $username);
    				    $stmt->execute();
        				
        			}
				}
				
				$expire_time = time() + 604800;  // 7 Days
				setcookie('account_access_key', $token, $expire_time, '/', '');
                return true;

            } else {
                // Passwords do not match, return false
                return false;
            }
        } else {
            // No matching username or email found, return false
            return false;
        }
    }
}


// Verifies cookie in database and returns account creds as session vars.  
// Renews browser cookie exp time to 7 days, though actual token expire defined inside of token 
function verify_by_key($account_access_key) {

	if (!verify_token_exp($account_access_key)) {
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

				$_SESSION['id'] = $id;
				$_SESSION['fullname'] = $fullname;
				$_SESSION['email'] = $email;
				$_SESSION['username'] = $username;

				$expire_time = time() + 604800;  // 7 Days
				setcookie('account_access_key', $account_access_key, $expire_time, '/');
				return true;

			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}