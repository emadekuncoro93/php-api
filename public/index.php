<?php
require "../bootstrap.php";
use Src\Controller\UserController;
use Src\Controller\CheckoutController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
$path = $uri[1];

// get user id
$userId = null;
if (isset($uri[2])) {
    $userId = (int) $uri[2];
}

$requestMethod = $_SERVER["REQUEST_METHOD"];
switch($path){
    case 'user':
        $controller = new UserController($dbConnection, $requestMethod, $userId);
    break;
    case 'checkout':
        $controller = new CheckoutController($dbConnection, $requestMethod, $userId);
    break;
    default:
        $path = null;
    break;

}

if ($path == null) {
    header("HTTP/1.1 404 Not Found");
    exit();
}
$controller->processRequest();
