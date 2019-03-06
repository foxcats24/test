<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('VIEWS_PATH', ROOT . DS . 'views');


try{
require_once (ROOT.DS.'lib'.DS.'init.php');



$uri = $_SERVER['REQUEST_URI'];

session_start();
App::run($uri);

} catch (Exception $e){
    echo "<b style='color: red'>" . $e->getMessage() . PHP_EOL . "</b>>" . "<br>";
    echo $e->getFile() . ' on line: ' . $e->getLine();
}