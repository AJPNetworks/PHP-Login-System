<?php

$settingMenuLinkStructure = '?_=account_settings&opt=';
$settingDefaultPage = "profile";

?>

<div class="container-fluid content">
    <div class="row">
        <div class="col-md-3 settings-menu">
            <ul>
                <a href="#" class="profile" onclick="menu_get('profile')"><li class="text-purple">Profile</li></a>
                <a href="#" class="notifications" onclick="menu_get('notifications')"><li>Notifications</li></a>
                <a href="#" class="privacy" onclick="menu_get('privacy')"><li>Privacy</li></a>
                <a href="#" class="appearance" onclick="menu_get('appearance')"><li>Appearance</li></a>
                <a href="#" class="account" onclick="menu_get('account')"><li>Account</li></a>
            </ul>
        </div>
        <div class="col-md-9 settings-content">

            <!-- This gets the content for the individual settings menus -->
            <?php
            if (isset($_GET['opt'])) {
                require $_SERVER['DOCUMENT_ROOT'] . '/src/php/home/pages/account_settings/'.$_GET['opt'].'.php';
            } else {
                require $_SERVER['DOCUMENT_ROOT'] . '/src/php/home/pages/account_settings/'.$settingDefaultPage.'.php';
            }
            ?>

        </div>
    </div>
</div>

<script type="text/javascript" src="/src/js/home/get_settings_pages.js"></script>
