<?php
session_start();
$_SESSION = array();
session_destroy();


$expire_time = time() - 3600;
setcookie('account_access_key', '', $expire_time, '/');

header("Location: /");
?>
