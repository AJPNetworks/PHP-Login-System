<?php

// This is the contact email sent in the verification email that users can contact to get support or whatever
$contactEmail = "contact@example.com";

$verifyEmailSend_smtpHost = "mail.exampl.com";
$verifyEmailSend_fromEmail = "noreply@exaple.com";
$verifyEmailSend_smtpUsername = "noreply@exaple.com";
$verifyEmailSend_smtpPassword = "mySuperSecretPassword123";
$verifyEmailSend_fromName = "Account Registration";
$verifyEmailSend_port = 587;  // we use tls and not ssl(465)..you can change this if needed in the /hidden/system_functions.php
$verifyEmailSend_subject = "Verify your email";

$verifyEmailSend_body = '

<!DOCTYPE html>
<html>
    <head><link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet"></head>
    <body style="font-family: \'Raleway\', \'Arial\', sans-serif; background-color: #fff; color: black width: 100%; margin: 0 auto; text-align: center;">
        <br>
        <h1 style="margin: 50px 0;">Verify Your Email</h1>
        <h3 style="margin: 50px 0;">Your verification code is</h3>
        <h1 style="color: #0f0; font-weight: bold; font-size: 48px; padding: 20px; max-width: 99%; margin: auto; border-radius: 15px; margin-bottom: 100px;">'.$otp.'</h1>
        <p>Thank you for registering with my website!<br><br>This email is in response to an account you are trying to create with us,  if you did not expect this email please contact us at <a href="mailto:'.$contactEmail.'" style="color: white;">'.$contactEmail.'</a>.<br>Please do not reply to this email, this email inbox is not monitored<p>
        <br>
        <footer style="width: 100%; text-align: center;">
            <p>My Company &copy; 2023. | All Rights Reserved<br><br></p>
        </footer>
    </body>
</html>';


$verifyEmailSend_altBody = "Your email verification code is $otp. Thank you for registering on my website!  If you did not expect this email or are unware of this account, please contact us at $contactEmail";
