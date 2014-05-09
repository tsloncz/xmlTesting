<?php
/*Takes in string sent from client
* Saves as file to users directory
*/
session_start();

$userName = $_SERVER['PHP_AUTH_USER'];
        $_SESSION['userName'] = $userName;
        $_SESSION['directory'] = getcwd() . "/" . $userName;
$uploadType = 'file';
$xmlString = $_POST["xmlString"];
$uploadType = $_POST["uploadType"];
$fileName = $_POST["fileName"];
$dir = $_SESSION['directory'] . "/";

$file = fopen($dir . $fileName,"w");
echo fwrite($file,$xmlString);
fclose($file);

?>