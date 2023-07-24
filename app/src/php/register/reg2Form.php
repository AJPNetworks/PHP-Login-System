<?php

if (isset($_POST['emailOTP'], $_POST['username'], $_POST['email'])) {

	require_once $_SERVER['DOCUMENT_ROOT']. '/../hidden/system_functions.php';


	$givenOtp = $_POST['emailOTP'];
	$email = $_POST['email'];
	$username = $_POST['username'];

	$con = init_db_connection("mydatabase");

	if ($stmt = $con->prepare('SELECT otp FROM `otp_cache` WHERE username=? AND sent_to=? AND type=?')) {
	    
	    $otpType = "email_reg_verify";
	    $stmt->bind_param('sss', $username, $email, $otpType);
	    $stmt->execute();  // Execute the statement first
	    
	    $stmt->bind_result($otp);  // Bind the result
	    $stmt->fetch();  // Fetch the data
	    
	    if ($otp == null) {  // If there is no OTP found, $otp will be null
	        header("HTTP/1.1 500 No OTP Found");
	        exit();
	    }
	    
	    $stmt->close();
	    
	    if ($otp == $givenOtp) {
	        if (final_setup() == true) {
	        	
	        	login();
	            header("HTTP/1.1 201 Created");
	            exit();

	        } else {
	            header("HTTP/1.1 500 Final Setup");
	            exit();
	        }
	    } else {
	        header("HTTP/1.1 406 OTP Invalid");
	        exit();
	    }
	} else {
	    header("HTTP/1.1 500 Prepare Failed");
	    exit();
	}


} else {
	header("HTTP/1.1 406 Not Acceptable");
	exit();
}




function final_setup() {
	global $con;
	global $username;
	clear_otp_by_username($username);

	if ($stmtOtpCache = $con->prepare('SELECT special FROM `accounts` WHERE username=?')) {
		$stmtOtpCache->bind_param('s', $username);
		$stmtOtpCache->execute();

		$stmtOtpCache->bind_result($specialColumn);
		if (!$stmtOtpCache->fetch()) {
			header("HTTP/1.1 500 Special Pull Failed");
			exit();
		}
		
		$stmtOtpCache->close();
	} else {
		header("HTTP/1.1 500 Special Failed Prepare");
		exit();
	}

	// Cleaning the special field dynamically to tell the system that the account's email is now verified
	$tempSpecialColumn = str_replace('nvEmail', '', $specialColumn);
	$tempSpecialColumn2 = str_replace('||', '|', $tempSpecialColumn);
	if (substr($tempSpecialColumn2, 0, 1) === '|') {
    	$newSpecialColumn = substr($tempSpecialColumn2, 1);
	}

	if ($stmtOtpCache = $con->prepare('UPDATE `accounts` SET special=? WHERE username=?')) {
		$stmtOtpCache->bind_param('ss', $newSpecialColumn, $username);
		if(!$stmtOtpCache->execute()) {
			header("HTTP/1.1 500 New Special Failed Execute");
			exit();
		}
		
		$stmtOtpCache->close();
	} else {
		header("HTTP/1.1 500 New Special Failed Prepare");
		exit();
	}

	// Add more setup functions here if needed for account creation

	return true;

}




function clear_otp_by_username($username) {
    global $con;
    $otpType = "email_reg_verify";

    if ($stmtOtpCache = $con->prepare('DELETE FROM `otp_cache` WHERE username=? AND type=?')) {
        $stmtOtpCache->bind_param('ss', $username, $otpType);
        $stmtOtpCache->execute();
        $stmtOtpCache->close();
    }
}


function login() {
	global $username;
	global $con;

    $stmt = $con->prepare('SELECT id, fullname, email FROM accounts WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->bind_result($id, $fullname, $email);
    $stmt->fetch();
    $stmt->close();

	$_SESSION = array();

	$token = generate_token();
	$stmt = $con->prepare('UPDATE accounts SET account_access_key=?  WHERE username = ?');
	$stmt->bind_param('ss', $token, $username);
	$stmt->execute();
	$stmt->close();

	$expire_time = time() + 604800;  // 7 Days
	setcookie('account_access_key', $token, $expire_time, '/', '');
	return true;

}