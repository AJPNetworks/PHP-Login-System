<?php
$homeLink = "/";
$aboutLink = "?_=about";
?>

<nav class="navbar navbar-expand-lg bg-body-light bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand text-purple-static" href="<?=$homeLink?>"><img height="50px" src="http://<?=$_SERVER['HTTP_HOST']?>/src/img/icon.png"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class=""><i class="fa-solid fa-bars"></i></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">


      <!--Left Side Nav Bar-->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="<?=$aboutLink?>">About</a>
        </li>
      </ul>


      <!--Right Side Nav Bar-->
      <ul class="navbar-nav me-3 mb-lg-0 d-flex">
        <li class="nav-item"><a class="nav-link" aria-current="page" data-bs-toggle="modal" data-bs-target="#LoginModal">Login</a></li>
        <li class="nav-item"><a class="nav-link" aria-current="page" data-bs-toggle="modal" data-bs-target="#RegModal">Register</a></li>
      </ul> 
    
    </div>
  </div>
</nav>