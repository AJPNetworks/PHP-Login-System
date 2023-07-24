<?php
$loginHomeLink = "?_=home";
$btnLink1 = "?_=home";  // Replace this with the file name inside of the src pages folder without the .php
$btnLink2 = "?_=home";  // Replace this with the file name inside of the src pages folder without the .php
$accountSettingsLink = "?_=settings";
$logoutLink = "/logout";
?>
<nav class="navbar navbar-expand-lg bg-body-light bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand text-purple-static" href="<?=$loginHomeLink?>"><img height="40px" src="http://<?=$_SERVER['HTTP_HOST']?>/src/img/icon.png"></a></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class=""><i class="fa-solid fa-bars"></i></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      
      <!--Left Side Nav Bar-->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="<?=$btnLink1?>">Button 1</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newChatModal" href="<?=$btnLink2?>">Button 2</a>
        </li>
      </ul>


      <!--Right Side Nav Bar-->
      <ul class="navbar-nav me-3 mb-lg-0 d-flex">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?=$_SESSION['firstname'] . " " .  $_SESSION['lastname']?> &nbsp;&nbsp;<i class="fa-solid fa-user"></i>&nbsp;&nbsp;</a>
          
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?=$accountSettingsLink?>">Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?=$logoutLink?>">Logout</a></li>
          </ul>
          
        </li>
      </ul> 
    </div>
  </div>
</nav>
<div class='nav-compensation'></div>