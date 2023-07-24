<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/src/php/auth/login_auth.php';

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="http://<?=$_SERVER['HTTP_HOST']?>/src/css/override.css">
    <link rel="stylesheet" type="text/css" href="http://<?=$_SERVER['HTTP_HOST']?>/src/css/main.css">
  </head>
  <body>

<?php
include $_SERVER['DOCUMENT_ROOT'].'/src/php/landing/navbar.php';

include $_SERVER['DOCUMENT_ROOT'].'/src/html/landing/modals.html';
?>

<?php



// Gets the page to display by the get request value of _
if (isset($_GET['_'])) {
    require $_SERVER['DOCUMENT_ROOT'] . '/src/php/landing/pages/'.$_GET['_'].'.php';

} else {
    require $_SERVER['DOCUMENT_ROOT'] . '/src/php/landing/pages/home.php';
}

?>





<footer class="footer mt-auto py-3 bg-dark text-white fixed-bottom">
    <div class="container text-center">
        <span>My Company &copy; <?php echo date("Y"); ?>. | All Rights Reservered</span>
    </div>
</footer>

    <!-- This is the script that POSTs the loging creds to the server, current page (this page in this case) -->
    <script src="http://<?=$_SERVER['HTTP_HOST']?>/src/js/login/login_send.js" type="text/javascript"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>
