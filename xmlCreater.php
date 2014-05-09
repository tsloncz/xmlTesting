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
        <?php
/*
Elements can have:
	 only child elements
	 attributes with valuable information
	 attributes with valuable info and child elements
	 csv formatted body with an attribute that contains column names
*/

//Convert mixed type XML to properly formated XML
 /*
$startTime = new DateTime();
$filename = 'data/file.xml';
if (file_exists($filename)) 
    unlink($filename);
    
$handle = fopen($filename, 'w') or die('Cannot open file:  '.$filename);
$start = '<?xml version="1.0" encoding="utf-8"?>
<WDB xmlns="http://ns">
<Stations StationID="ARBA" Station_Name="Balcarce" Place_Name="Argentina" Lat="-37.75" Long="-58.3" Elev="10" Tav="15" Amp="10" Tmht="2" Wmht="2">
<Storm_Intensity>
    <Storm_Intensity Month="1" SIValue="0.1" />
    <Storm_Intensity Month="2" SIValue="0.1" />
    <Storm_Intensity Month="3" SIValue="0.1" />
    <Storm_Intensity Month="4" SIValue="0.1" />
    <Storm_Intensity Month="5" SIValue="0.1" />
    <Storm_Intensity Month="6" SIValue="0.1" />
    <Storm_Intensity Month="7" SIValue="0.1" />
    <Storm_Intensity Month="8" SIValue="0.1" />
    <Storm_Intensity Month="9" SIValue="0.1" />
    <Storm_Intensity Month="10" SIValue="0.1" />
    <Storm_Intensity Month="11" SIValue="0.1" />
    <Storm_Intensity Month="12" SIValue="0.1" />
</Storm_Intensity><weather>';
$endString = '</weather></Stations></WDB>';

fwrite($handle, $start);

//Parser XML seems to be better but saving this incase
//Simple XML Reference http://www.w3schools.com/Php/php_ref_simplexml.asp
$sxe=simplexml_load_file("data/ARBASXA1.wdb.xml");

foreach ($sxe->children() as $child)
  {
	 foreach ($child->children() as $grandChild)
         {
		 foreach($child->attributes() as $a => $b)
		{
			//$data +=  $a + $b;
		}
		 //If count()>0 the element has children and is not in csv format
		 //Should write 2 functions to handle either case
		 if (  $grandChild->count() == 0 )
                        $element = $grandChild;	
		 	//printCsvElement( $grandChild );
		 	//echo "content: " . $grandChild . "<br>";
		 else
		 	foreach($grandChild->attributes() as $a => $b)
		  	{
				//echo "&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp" . $a,'=>"',$b[0],"\"<br>";
				//echo '';
		  	}
		 foreach ($grandChild->children() as $greatGrandChild)
		 {
			 //echo "&nbsp &nbsp &nbsp &nbsp<font color='violet'>" . $greatGrandChild->getName() . "</font><br>";
			 foreach($greatGrandChild->attributes() as $a => $b)
			  {
			  	//echo "&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp" . $a,'="',$b,"\"<br>";
			  }
		 }
	 }
  
  }

    $sourceLine = '<weather Year="[year]" DOY="[doy]" SRAD="[srad]" Tmax="[tmax]" Tmin="[tmin]" Rain="[rain]" DewP="[dewp]" Wind="[wind]"/>';
    $replaceString = $sourceLine;
	$csv =  ((string) $element);
	//$csv = explode("," , $csv);
        $length = strlen($csv);
	$numColumns;
        $val = '';
        $i = 0;
        
	for($j = 0; $j<200; $j++)
	{
                if( $csv[$j] != ',')
                {
                    if( $csv[$i] != '\r')
                    {
                        $val .= $csv[$j];
                    }
                }
                else
                {
                    $val = ltrim($val);
                    //echo $i . '<br>';
                    switch ($i) {
                    case 0:
                        $replaceString = str_replace("[year]",$val,$replaceString);
                        break;
                    case 1:
                        $replaceString = str_replace("[doy]",$val,$replaceString);
                        break;
                    case 2:
                        $replaceString = str_replace("[srad]",$val,$replaceString);
                        break;
                    case 3:
                        $replaceString = str_replace("[tmax]",$val,$replaceString);
                        break;
                    case 4:
                        $replaceString = str_replace("[tmin]",$val,$replaceString);
                        break;
                    case 5:
                        $replaceString = str_replace("[rain]",$val,$replaceString);
                        break;
                    case 6:
                        $replaceString = str_replace("[dewp]",$val,$replaceString);
                        break;
                    case 7:
                        $replaceString = str_replace("[wind]",$val,$replaceString);
                        break;
                    }
                    $val = '';
                    $i++;
                }
                //if( $i == 8)
                if( $csv[$j] == "\n" && $i != 0)
                {
                    fwrite($handle, $replaceString . "\n");
                    $replaceString = $sourceLine;
                    $i = 0;
                }
        }
         

fwrite($handle, $endString);
fclose($handle);
$endTime = new DateTime();
$diff = $endTime->diff($startTime);
print "Time to create: " . $diff->s . "seconds";*/

//Parse, time and displays from proper XML
echo "<br><br>Beginning to open and parse proper XML file<br>";
$startTime = new DateTime();
$experimentFile = "data/AgMIPWHAU.xdb.xml";
$weatherFile = "data/ARBASXA1.wdb.xml";
$xml = simplexml_load_file( $weatherFile );//load properly formattd XML
dispProperXML( $xml );

$endTime = new DateTime();
$diff = $endTime->diff($startTime);

echo "Completed parsing proper xml in " . $diff->s . " seconds";

//todo
//Use functions inorder to traverse all depths
function dispProperXML($sxe)
{
    echo "file type: <font color='red'>" . $sxe->getName() . "</font><br>";
    foreach ($sxe->children() as $child)
    {
        echo "<font color='green'>" . $child->getName() . "</font>";
        echo " has " . $child->count() . " children<br>";
        //Check for attributes
        if( $child->attributes() != NULL)
        {
            echo "<table border=1><tr>";
            foreach($child->attributes() as $a => $b)
            {
                echo "<th>" . $a . "</th>";
            }
            echo "</tr>";
            foreach($child->attributes() as $a => $b)
            {
                echo "<td><input type='text' value='$b'></input></td>";
            }
            echo "</tr></table><br>";
        }
	 foreach ($child->children() as $grandChild)
         {
            echo "<font color='blue'>" . $grandChild->getName() . "</font>";
            echo " has " . $grandChild->count() . " children<br>";
            $needHeading = 1;
               echo "<table border=1><tr>";
            foreach ($grandChild->children() as $greatGrandChild)
            {
               if($needHeading == 1)
               {
                foreach($greatGrandChild->attributes() as $a => $b)
                 {
                     echo "<th>" . $a . "</th>";
                 }
                 $needHeading = 0;
               }
                echo "</tr>";
                $position = 0;
                foreach($greatGrandChild->attributes() as $a => $b)
                {
                    if( $position%count($greatGrandChild->attributes()) )
                        echo "</tr><tr>";
                    echo "<td><input type='text' value='$b'></input></td>";
                }
            }
                echo "</tr></table>";
	 }
    }
}

function dispElement($element)
{

}
        ?>
    </body>
</html>
