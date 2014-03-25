<?php
/**
 * This file loads the app files.
 * 
 * First, we load the config file. Next, we load app.php which registers all servies and includes the autoloader. We also load the controller file which includes all business logic. Then we run the app.
 */

require_once __DIR__.'/../src/app.php';

require_once __DIR__.'/../src/controllers.php';

$app->run();
?>
