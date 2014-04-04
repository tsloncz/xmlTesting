<?php

$uploadType = 'file';
$xmlString = $_POST["xmlString"];
$uploadType = $_POST["uploadType"];

$file = fopen("uploads/test.xml","w");
echo fwrite($file,$xmlString);
fclose($file);

?>