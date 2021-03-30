<?php
session_start();
session_destroy();

$location = 'Location: https://' . $_SERVER['SERVER_NAME'] . '/login';
header($location, true, 302);
exit;
