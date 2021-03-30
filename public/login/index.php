<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/fbConfig.php';

session_start();

$fb = new Facebook\Facebook([
    'app_id' => FB_APP_ID,
    'app_secret' => FB_APP_SECRET,
    'default_graph_version' => 'v2.10',
]);

$redirectURL = FB_APP_REDIRECT;
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];
$loginUrl = $helper->getLoginUrl($redirectURL, $permissions);

include_once '../components/page-head.php';
?>

<div class="h-full flex">
    <div class="grid grid-rows-4 bg-white overflow-hidden text-blue-800 m-auto h-1/2 w-10/12 sm:w-1/2 lg:w-1/3 shadow-2xl rounded-2xl">
        <div class="flex bg-gray-700 p-2 tex-white text-2xl text-center text-white">
            <h1 class="m-auto">
                BMI Calculator: Login
            </h1>
        </div>
        <div class="login-bg flex row-span-3">
            <a
                    class="rounded bg-blue-500 block text-center w-10/12 m-auto hover:bg-blue-700 py-2 px-4 text-white"
                    href="<?php echo $loginUrl ?>">Connect with Facebook!</a>
        </div>
    </div>
</div>


<?php

include_once '../components/page-end.php';
?>


