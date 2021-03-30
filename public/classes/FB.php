<?php

namespace Custom;

use Facebook\Facebook;
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../config/fbConfig.php';

class FB
{
    static function init() : Facebook{
        return new Facebook([
            'app_id' => FB_APP_ID,
            'app_secret' => FB_APP_SECRET,
            'default_graph_version' => 'v2.10',
        ]);
    }
}