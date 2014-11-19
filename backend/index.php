<?php


$site_path = realpath(dirname(__FILE__));
define ('__SITE_PATH', $site_path);

include_once 'includes/init.php';
include_once('application/WebServiceException.php');


error_log ("SITEPATH: " .  __SITE_PATH);

try
{
    $router = new Router($registry);
    $router->setPath (__SITE_PATH . '/controller');
    $router->loader();
    header("HTTP/1.1 200 OK");
}
catch (WebServiceException $e)
{
    $err["id"] = 0;
    $err["code"] = $e->getCode();
    $err["message"] = $e->getMessage();
    $registry->data = json_encode($err);
    header("HTTP/1.1 500 Server Error");
}

header('Content-type: application/json');
print ( $registry->data);



