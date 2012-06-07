<?php
/**
 * Step 1: Require the Slim PHP 5 Framework
 *
 * If using the default file layout, the `Slim/` directory
 * will already be on your include path. If you move the `Slim/`
 * directory elsewhere, ensure that it is added to your include path
 * or update this file path as needed.
 */
define('PROJECT_DIR', dirname(__FILE__) . '/..');
require PROJECT_DIR . '/vendor/Slim/Slim/Slim.php';
require PROJECT_DIR . '/vendor/Slim-Extras/Views/TwigView.php';
require PROJECT_DIR . '/config/config.php';

TwigView::$twigDirectory = PROJECT_DIR . '/vendor/Twig/lib/Twig';

/**
 * Step 2:Setting Framework config
 */
$app = new Slim($config);

require PROJECT_DIR . '/config/bootstrap.php';
require PROJECT_DIR . '/config/routes.php';

/**
 * Step 3: Runing Application
 */
$app->run();
