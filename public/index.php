<?php
session_start();

if(!isset($_SESSION['facebook_access_token'])){
    $location = 'Location: login';
    header($location, true, 302);
    exit;
}

include_once './components/page-head.php';

include_once './components/home-view.php';

include_once './components/page-end.php';



