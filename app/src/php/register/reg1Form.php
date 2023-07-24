<?php

if (isset($_POST['fullname'], $_POST['email'], $_POST['username'], $_POST['password'])) {

	$formResult = form_validation($_POST['fullname'], $_POST['email'], $_POST['username'], $_POST['password']);

	if ($formResult != true) {
		header("HTTP/1.1 406 Validation Failed");
		exit();
	}

	require_once $_SERVER['DOCUMENT_ROOT']. '/../hidden/system_functions.php';

	$fullname = $_POST['fullname'];
	$email = $_POST['email'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$hashedpassword = password_hash($password, PASSWORD_DEFAULT);
	$special = "nvEmail";

	$con = init_db_connection("mydatabase");

	if ($stmt = $con->prepare('SELECT * FROM `accounts` WHERE username=?')) {
	    $stmt->bind_param('s', $username);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    if ($result->num_rows >= 1) {
	        header("HTTP/1.1 406 Username Taken");
	        exit();
	    }
	    $stmt->close();
	} 

	if ($stmt = $con->prepare('SELECT * FROM `accounts` WHERE email=?')) {
	    $stmt->bind_param('s', $email);
	    $stmt->execute();
	    $result = $stmt->get_result();
	    if ($result->num_rows >= 1) {
	        header("HTTP/1.1 406 Email Taken");
	        exit();
	    }
	    $stmt->close();
	} 

	if ($stmt = $con->prepare('INSERT INTO `accounts` (fullname, email, username, password, special) VALUES (?, ?, ?, ?, ?)')) {

	    $stmt->bind_param('sssss', $fullname, $email, $username, $hashedpassword, $special);
	    if ($stmt->execute()) {

	    	$otp = generateVerificationCode();

	    	if ($stmt = $con->prepare('INSERT INTO `otp_cache` (username, type, otp, sent_to) VALUES (?, ?, ?, ?)')) {
				$type = "email_reg_verify";
	    		$stmt->bind_param('ssss', $username, $type, $otp, $email);
	    		if ($stmt->execute()) {

					$parts = explode("|", $fullname);
					$firstname = $parts[0];
					$lastname = $parts[1];  // We get to here propperly

					if (send_reg_ver_email($email, $firstname, $lastname, $otp) == false) {
						clear_database_entry_by_username($username);
						header("HTTP/1.1 500 Email Send Failure");
						exit();

					} else {
						$con->close();
						header("HTTP/1.1 201 Created");
						exit();
					}

	    		} else {
	    			clear_database_entry_by_username($username);
					header("HTTP/1.1 500 Internal Error");
					exit();
	    		}
	    		$stmt->close();

	    	} else {
	    		clear_database_entry_by_username($username);
				header("HTTP/1.1 500 Internal Error");
				exit();
	    	}
	
		} else {
			clear_database_entry_by_username($username);
			header("HTTP/1.1 500 Internal Error");
			exit();
		}
		$stmt->close();

	} else {
		clear_database_entry_by_username($username);
		header("HTTP/1.1 500 Internal Error");
		exit();
	}

	$con->close();
	header("HTTP/1.1 201 Created");
	exit();

} else {
	header("HTTP/1.1 400 Empty Form");
	exit();	
}
clear_database_entry_by_username($username);




function form_validation($fullname, $email, $username, $password) {

		$decodePass = base64_decode(base64_decode($password));
	if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,32}$/", $decodePass)) {
    	header("HTTP/1.1 406 Password Invalid");
    	exit;
	} elseif (!preg_match("/^[^\s@]+@[^\s@]+\.[^\s@]{2,7}$/", $email)) {
    	header("HTTP/1.1 406 Email Invalid");
    	exit;
	} elseif(!preg_match("/^[\s\S]*\|[\s\S]*$/", $fullname)) {
        header("HTTP/1.1 406 Name Invalid");
        exit;
    } else {
    	$decodePass = null;
    	return true;
    }
}


function clear_database_entry_by_username($username) {
    global $con;

    // Prepare the delete statement for the 'accounts' table
    if ($stmtAccounts = $con->prepare('DELETE FROM `accounts` WHERE username=?')) {
        $stmtAccounts->bind_param('s', $username);
        $stmtAccounts->execute();
        $stmtAccounts->close();
    }

    // Prepare the delete statement for the 'otp_cache' table
    if ($stmtOtpCache = $con->prepare('DELETE FROM `otp_cache` WHERE username=?')) {
        $stmtOtpCache->bind_param('s', $username);
        $stmtOtpCache->execute();
        $stmtOtpCache->close();
    }
}
