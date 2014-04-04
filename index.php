<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="data/jquery-ui.css" />  
        <script type="text/javascript" src="data/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="data/jquery-ui.js"></script>
        <script type="text/javascript" src="script.js"></script>
        <title></title>
    </head>
    <body>
        <form action="upload.php" method="post" accept-charset=""enctype="multipart/form-data">
            <label for="file">Filename:</label>
            <input type="file" name="file" id="file"><br>
            <input type="submit" name="submit" value="Submit">
        </form>
        <?php
/* To test different file types XML and JSON for
 * size
 *      Create file for each format and compare
 * parsing speed
 *  Record how long it takes to do each of the following
 *      Parse and output in current format
 *      Parse and output in prope XML
 *      Parse and output in JSON
 * 
 * 
 * Element can have data 
 *      in just it's attributes
 *      in it's attributes and children
 *      as csv
 *      in it's childrens attributes
 * 
 * 
 * Algorithm
 * If element has attributes
 *  print them
 */

$XDBFile = "data/AgMIPWHAU.xdb.xml";
$weatherFile = "data/ARBASXA1.wdb.xml";
$cropFile = "data/crops.cdb.xml";
$soilFile = "data/face.sdb.xml";
$tipFile = "data/salus.ddb.xml";
$gdbFile = "data/salusshort.gdb.xml";
$testFile = "data/test.xml";
$startTime = new DateTime();
//print $startTime->getTimestamp() . '<br>';

//Simple XML Reference http://www.w3schools.com/Php/php_ref_simplexml.asp
//print '<b>Using Simple XML</b>' . "<br>";
$sxe=simplexml_load_file( $weatherFile );
dispFile( $sxe);


$endTime = new DateTime();
$diff = $endTime->diff($startTime);
print "Time to create: " . $diff->s . "seconds";
echo "<br><button onclick='createXml()'>Submit File</button>";

function dispFile( $sxe )
{
    $name = $sxe->getName();
    echo "file type: <span class='fileType' id='$name' style='color:red'>" . $sxe->getName() . "</span><br>";
    foreach ($sxe->children() as $child)
     {
 	 //echo "<font color='green'>" . $child->getName() . "</font>";
         handleElement( $child );  
    }   
}
function handleElement( $element )
{
    $name = $element->getName();
    $numAttributes = count($element->attributes());
    $dataInChildren = 0;
    $printedName = 0;
    //If element has attributes print them in table form
    //Unless element has no children then data is in csv
    
    if( /*$numAttributes >= 1 &&*/ $element->count() == 0 && ((string) $element) != '') // data is in csv format
    {
        $printedName = 1;
        printDataCsv( $element );
    }
    else if( $numAttributes >= 1  )
    {
        if(  $element->count() > 0 && checkForDataInChildren($element) == 1 )// data is in the child attributes
        {
            echo "<span class='element' id='attributeElementwithChildData' name='$name' style='color:blue'>" . $element->getName() . "</span> ";
            $printedName = 1;
            echo "Printing attribute table<br>";
            $tableName = $element->getName();
            echo "<table class='attributeTable' id='$tableName' border=1><tr>";
            echo ((string) $element);
            foreach($element->attributes() as $a => $b)
            {
                echo "<th class='heading'>" . $a . "</th>";
            }
            echo "</tr>";
            foreach($element->attributes() as $a => $b)
            {
                echo "<td class='attributeValue' id='$a'><input type='text' value='$b'></input></td>";
            }
            echo "</tr></table><br>";
            printDataChildren( $element );
            $dataInChildren = 1;
        }
        else
        {
            echo "<span class='element' id='attributeElement' name='$name' style='color:blue'>" . $element->getName() . "</span> ";
            $printedName = 1;
            echo "Printing attribute table<br>";
            $tableName = $element->getName();
            echo "<table class='attributeTable' id='$tableName' border=1><tr>";
            echo ((string) $element);
            foreach($element->attributes() as $a => $b)
            {
                echo "<th class='heading'>" . $a . "</th>";
            }
            echo "</tr>";
            foreach($element->attributes() as $a => $b)
            {
                echo "<td class='attributeValue' id='$a'><input type='text' value='$b'></input></td>";
            }
            echo "</tr></table><br>";
        }
    }
    if(  $element->count() > 0 && checkForDataInChildren($element) == 1 && $dataInChildren == 0)// data is in the child attributes
    {
 	echo "<span class='element' id='dataInChildrenElement' name='$name' style='color:blue'>" . $element->getName() . "</span> ";
        echo "Data in children<br>";
        printDataChildren( $element );
        $dataInChildren = 1;
    }
    else if ( $printedName == 0)
    {
 	echo "<span class='element' id='wrapperElement' name='$name' style='color:blue'>" . $element->getName() . "</span><br> ";
        $printedName = 1;
    }
    if($dataInChildren == 0)
    {
        foreach ($element->children() as $child)
        {
            handleElement( $child );
        }
    }
}
function printDataCsv( $element )
{
	$csv =  ((string) $element);
        $length = strlen($csv);
        $val = '';
        $i = 0;
	//$csv = explode("," , $csv);
	$numColumns;
        $tableName = $element->getName();
        if(count($element->attributes()) > 0 )
        {
 	echo "<span class='element' id='csvTableElement' name='$tableName' style='color:blue'>" . $element->getName() . "</span> ";
        echo "Data in CSV format<br>";
            echo "<table class='csvTable' id='$tableName' border='1' border-collapse='collapse' cellpadding='5'><tr>";
            foreach($element->attributes() as $a => $b)
            {
                    $bArray = explode(",",$b);
                    $numColumns = count($bArray);
                    foreach($bArray as $val)
                    {
                            echo "<th>" . $val . "</th>";
                    }
            echo "</tr><tr>";
            }
            // Create table from csv as array
            /*
            foreach( $csv as $a => $b )
            {
                    if( ($a % $numColumns) == 0 && $a != 0)
                    {
                            echo "</tr><tr>";
                    }
                    echo "<td align='center'><input type='text' size='5' value='$b'></input></td>";
            }*/
            //Create table from csv as string
            for($j=0; $j<$length; $j++)
            {
                if( $csv[$j] != ',')
                {
                    if( $csv[$j] == "\n" )
                    {
                        echo "</tr><tr>";
                        $i=0;
                    }
                        $val .= $csv[$j];
                }
                else
                {
                    $i++;
                    $val = ltrim($val);
                    echo "<td align='center'><input type='text' size='5' value='$val'></input></td>";
                    //echo $val;
                    $val = '';
                }
            }
            echo "</tr></table><br><br>";
        }
        else
        {
            echo "<span class='element' id='csvElement' name='$tableName' style='color:blue'>" . $element->getName() . "</span> ";
            echo "Data in CSV format<br>";
            echo "<span class='csvData' id='$tableName'><input type='text' size='30' value='$element'></input></span><br>";
        }

}

function printDataChildren( $element )
{
    $needTableHeadings = 1;
    $tableId = $element->getName(); //to reference table from parent element in html
    $tableName = $element->children()->getName();
    echo "<table class='childData' id='$tableId' name='$tableName' border=1><tr>";
    foreach ($element->children() as $child)
    {
       if($needTableHeadings == 1)
       {
        foreach($child->attributes() as $a => $b)
         {
             echo "<th>" . $a . "</th>";
         }
         $needTableHeadings = 0;
       }
        echo "</tr>";
        $position = 0;
        foreach($child->attributes() as $a => $b)
        {
            if( $position%count($child->attributes()) )
                echo "</tr><tr>";
            echo "<td id='$a'><input type='text' value='$b'></input></td>";
        }
    }
        echo "</tr></table><br>";
    
}

function checkForDataInChildren( $element )
{
    $numChildren = $element->count();
    $testName = $element->children()->getName();
    $dataChildTest = $element->children()->count(); //Must be zero
    $getData = 1;
    // If all children have the same name then data is in children
    foreach ($element->children() as $child)
    {
        if( $testName != $child->getName() || $child->count() != 0)
        {
            $getData = 0;
            break;
        }
    }
    if( $getData == 1 && $numChildren > 1)
        return 1;
    else
        return 0;
    
}



/*
Elements can have:
	 only child elements
	 attributes with valuable information
	 attributes with valuable info and child elements
	 csv formatted body with an attribute that contains column names
*/

//XML Testing
 
/* 
//PHP XML Parser Reference http://www.w3schools.com/Php/php_ref_xml.asp
//invalid xml file
echo "<b>Using Parse XML</b><br>";
$xmlfile = 'ARBASXA1.wdb.xml';
$xmlparser = xml_parser_create();
// open a file and read data
$fp = fopen($xmlfile, 'r');
$xmldata = fread($fp, 4096);

//Use xmlparse, to parse xmldata into an array called values
xml_parse_into_struct($xmlparser,$xmldata,$values);

xml_parser_free($xmlparser);
print_r($values);

//Print key and value pairs of associative array
function test_print($item2, $key)
{
    echo "&nbsp &nbsp &nbsp &nbsp $key. => $item2<br />\n";
}

echo "<br><br>";
foreach($values as $val)
{
	echo "&nbsp &nbsp" . $val[level] . '&nbsp'. $val[tag] . '<br>';
	if( is_array($val[attributes]) ) //Attribute is an array
		array_walk( $val[attributes] , 'test_print');
	else
		echo "&nbsp &nbsp &nbsp &nbsp &nbsp" . $val[attributes] . '<br>';
	if( $val[value] != ' ')
		echo  $val[value] . '<br>';
}
*/
        ?>
    </body>
</html>
