<?php
define('PROJECT_DOCUMENT_ROOT', __DIR__);
define('PROJECT_CLASSES_PATH' , __DIR__."/inc/classes");
define('PROJECT_INCLUDE_PATH' , __DIR__."/inc");

$project = str_replace($_SERVER['DOCUMENT_ROOT'], "",
 str_replace("\\", "/", __DIR__)
        );

@(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
    || $_SERVER['SERVER_PORT'] == 443)?
    $protokoll = "https://" :
    $protokoll = "http://";

@define('PROJECT_HTTP_ROOT', $protokoll.$_SERVER['HTTP_HOST'].$project);
?>
