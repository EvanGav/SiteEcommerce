
<html>
    <link rel="stylesheet" href="css/main.css"/>

</html>

<?php

use App\Controller\ControllerProduit;

require "../vendor/autoload.php";



if(isset($_REQUEST['controller']) == null){
    $controller = "Generic";
}

else{
    $controller = $_REQUEST['controller'];
}

$controllerClassName = "\\App\Controller\\" . "Controller".ucfirst($controller);

if (!class_exists($controllerClassName)) {
    header("HTTP/1.0 404 Not Found");
}

if (!isset($_REQUEST['action'])) {
    $action = "acceuil";
} else if (!in_array($_REQUEST['action'], get_class_methods($controllerClassName))) {
    $controllerClassName::error("L'action " . $_REQUEST['action'] . " n'existe pas !");
    $action = "acceuil";
}  else {
    $action = $_REQUEST['action'];
}

$session= \App\Lib\Session::getInstance();

$controllerClassName::$action();