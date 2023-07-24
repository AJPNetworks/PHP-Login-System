<?php

// This function was made just so we only need one function to connect and verify connection the the database
function init_db_connection($database) {

	require_once __DIR__.'/env/databases.php';
	
	$DATABASE_HOST = $db_host;
	$DATABASE_USER = $db_user;
	$DATABASE_PASS = $db_pass;
	$DATABASE_NAME = $database;

	$connection = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	if ( mysqli_connect_errno() ) {
		exit('Failed to connect to MySQL: ' . mysqli_connect_error());
	}
	return $connection;
}



// This is used for generating the browser login tokens
function generate_token($time = 604800) {
	$uniqueToken = bin2hex(random_bytes(64));
	$expirationTime = time() + $time;
	$tokenData = array('token' => $uniqueToken, 'exp' => $expirationTime);
	$base64Encode = base64_encode(json_encode($tokenData));
	$count = 0;
	$totalIterations = 2;
	
	while ($count < $totalIterations) {
	    $base64Encode = base64_encode($base64Encode);
	    $count++;
	}
  	return $base64Encode;
}

// And this is used for verifying browser login tokens
function verify_token_exp($base64Token) {
    $count = -1;   // -1 to account for the needed one extra iteration to match the generator
    $totalIterations = 2;

    while ($count < $totalIterations) {
        $base64Token = base64_decode($base64Token);
        $count++;
    }
    $tokenData = json_decode($base64Token, true);

    // Check that $tokenData is an array and contains the necessary keys
    if (!is_array($tokenData) || !isset($tokenData['token']) || !isset($tokenData['exp'])) {
        return null;
    }

    $token = $tokenData['token'];
    $expirationTime = $tokenData['exp'];

    if ($expirationTime <= time()) {
        return null;
    } else {
        return $token;
    }
}


// Email OTP code (could be for sms if you have that or another form of mfa)
function generateVerificationCode() {
    return rand(100000, 999999);
}


// This is the email that is sent out to verify that the user has access to the email
function send_reg_ver_email($email, $firstname, $lastname, $otp) {

	require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/env/smtp.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    try {

        $mail->isSMTP();
        $mail->Host = $verifyEmailSend_smtpHost;
        $mail->SMTPAuth = true;
        $mail->Username = $verifyEmailSend_smtpUsername;
        $mail->Password = $verifyEmailSend_smtpPassword;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $verifyEmailSend_port;
   	    $mail->setFrom($verifyEmailSend_fromEmail, $verifyEmailSend_fromName);
        $mail->addAddress($email, $firstname . " " . $lastname);
        $mail->Subject = $verifyEmailSend_subject;
        $mail->isHTML(true);
        $mail->Body = $verifyEmailSend_body;
        $mail->AltBody = $verifyEmailSend_altBody;
	
	    $mail->send();
	    $result = true;

	} catch (Exception $e) {
	    $result = false;
	}
	return $result;
}