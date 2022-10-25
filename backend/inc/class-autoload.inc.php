<?php
// +------------------------------------------------------------------------+
// | @author        : Michael Arawole (Logad Networks)
// | @author_url    : https://www.logad.net
// | @author_email  : logadscripts@gmail.com
// | @date          : 05 Oct, 2022 9:05 AM
// +------------------------------------------------------------------------+


define('BASE_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR);
define('BACKEND_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

spl_autoload_register('myAutoLoader');

function myAutoLoader($className) {
    $path = BACKEND_PATH . "/classes/";
    $extension = ".class.php";
    $fullPath = $path . $className . $extension;
    if (file_exists($fullPath)) {
        include_once $fullPath;
    }
    return false;
}
