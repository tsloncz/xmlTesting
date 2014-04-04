<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h3>XML to JSON</h3>
        <?php
/*
Elements can have:
	 only child elements
	 attributes with valuable information
	 attributes with valuable info and child elements
	 csv formatted body with an attribute that contains column names
*/

//Convert mixed type XML to properly formated XML

$startTime = new DateTime();
$filename = 'data/fileJson.json';
if (file_exists($filename)) 
    unlink($filename);
    
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);

$sxe=simplexml_load_file("data/file.xml");
$json = json_encode($sxe);
fwrite($handle, $json);
fclose($handle);
$endTime = new DateTime();
$diff = $endTime->diff($startTime);
print "Time to create: " . $diff->s . " seconds";
echo $json;

        ?>
    </body>
</html>
