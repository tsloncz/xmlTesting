<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
session_start();

function authenticate() {
    header("Location: http://salusmodel.glg.msu.edu/salusInterface/xmlTesting/");
    exit;
}

function printWelcome()
{
	echo "<h1>Welcome <span class='yellow-heading'>" . $_SESSION['userName'];
	echo "<form method='post'; action='index.php'> ";
	echo "<button type='submit' name='logout'>Logout</button></form></span></h1>";
}
function uploadFile( )
{
	if(isset($_POST['uploadFile']))
	{
		// echo "uploading file<br />";
		$dir = $_SESSION['directory'] . "/";
		$allowedExts = array("xml");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);
		if ((($_FILES["file"]["type"] == "text/xml")) && ($_FILES["file"]["size"] < 200000)
				&& in_array($extension, $allowedExts))
		{
			if ($_FILES["file"]["error"] > 0)
			{
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
			}
			else
			{
				//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				//echo "Type: " . $_FILES["file"]["type"] . "<br>";
				//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
			
				if (file_exists($dir . $_FILES["file"]["name"]))
				{
					echo "<b>Upload Status:</b> " . $_FILES["file"]["name"] . " already exists. ";
				}
				else
				{
					move_uploaded_file($_FILES["file"]["tmp_name"],
					$dir . $_FILES["file"]["name"]);
					//echo "Stored in: " . $dir . $_FILES["file"]["name"];
					$sxe=simplexml_load_file( $dir . $_FILES["file"]["name"] );
					if($sxe)
					{
						echo "<b>Upload Status:</b> " . $_FILES["file"]["name"] . " uploaded successfully<br />";
					}
					else
					{
						echo "<b>Upload Status:</b> " . "Sorry upload failed. Please try again.<br />";
					}
				}
			}
		}
		else
		{
			echo "<b>Upload Status:</b> " . "Invalid file";
		}
	}
}

function downloadFile( $file )
{
	if (file_exists($file)) 
	{
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
	}
}
 
if (!isset($_SERVER['PHP_AUTH_USER']) ||
    ($_POST['SeenBefore'] == 1 && $_POST['OldAuth'] == $_SERVER['PHP_AUTH_USER'])) {
    authenticate();
} 
    if(isset($_POST['logout'])) 
    { 
        echo "<h3>Thanks, see you next time " . $_SESSION['userName'] . "</h3>";
        unset($_SESSION['userName']); // etc only the user is necessary 
        setcookie(session_name(),'',time()-24*60*60); // or 86400 (24 hours) 
        session_destroy(); 
        exit();
    }    else if(isset($_SESSION['userName']))
    {
		printWelcome();
        //echo getcwd() . "<br>";
        $dir = $_SESSION['directory'];
        //echo $dir . "<br>";
        uploadFile();
		printUserFiles( $_SESSION['directory'] );
    }
    else if( isset($_SERVER['PHP_AUTH_USER']) )
    {
        $userName = $_SERVER['PHP_AUTH_USER'];
        $_SESSION['userName'] = $userName;
        $_SESSION['directory'] = getcwd() . "/" . $userName;
		printWelcome();
        uploadFile();
		printUserFiles( $_SESSION['directory'] );
    }
function printUserFiles( $dir )
{
	echo "<div id='myFiles'><h3>Your current files</h3><ul>";
	// Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) 
				{
                    if ($dh = opendir($dir)) 
					{
						while (($file = readdir($dh)) !== false) {
							if ($file != "." && $file != ".." && $file != ".htaccess") {
								echo "<li><a href=" . htmlentities($_SERVER['PHP_SELF']) . "?cmd=fileInterface&file=$file>" . $file . "</a></li>";
							}
						}
						echo "</ul></div>"; // close list of user's files
						closedir($dh);
					}
                }
                closedir($dh);
            }
        }
        else
        {
            echo "Sorry,open directory failed<br>";
        }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="data/jquery-ui.css" />  
        <script type="text/javascript" src="data/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="data/jquery-ui.js"></script>
        <script type="text/javascript" src="saveAndUpload.js"></script>
        <title></title>
    </head>
    <body>        <!--<form action="index.php" method="post" accept-charset=""enctype="multipart/form-data">
            <h3>Select type of file to edit</h3>
            <select name="fileType">
                <option value="soil">soil</option>
                <option value="weather">Weather</option>
                <option value="crop">crop</option>
                <option value="gdb">gdb</option>
                <option value="xdb">xdb</option>
              </select>
            <input type="submit" name="submit" value="Submit">
        </form>-->
        <!-- allows upload files
        <form action="index.php" method="post" accept-charset=""enctype="multipart/form-data">
            <label for="file">Filename:</label>
            <input type="file" name="file" id="file"><br>
            <button type="submit" name="uploadFile">Upload a file</button>
        </form> -->
        <?php
/* To test different file types XML and JSON for
 * size
 *      Create file for each format and compare
 * parsing speed
 *  Record how long it takes to do each of the following
 *      Parse and output in current format
 *      Parse and output in prop XML
 *      Parse and output in JSON
 *          Note: SimpleXml->toJSON adds weird @attributes and ruines JSON
 * 
 * 
 * Element can have data 
 *      in just it's attributes
 *          as headers for csv element string
 *          as attribute=>value pairs
 *      in it's attributes and children
 *      as csv
 *      in it's childrens 
 *          attributes
 *          as children element strings
 * 
 * 
 * Algorithm
 * If element has attributes
 *  print them
 */

//Simple XML Reference http://www.w3schools.com/Php/php_ref_simplexml.asp
//print $startTime->getTimestamp() . '<br>';
$XDBFile = "data/AgMIPWHAU.xdb.xml";
$weatherFile = "data/ARBASXA1.wdb.xml";
$cropFile = "data/crops.cdb.xml";
$soilFile = "data/face.sdb.xml";
$tipFile = "data/salus.ddb.xml";
$gdbFile = "data/salusshort.gdb.xml";
$testFile = "uploads/testNew.xml";
$startTime = new DateTime();
$sxe=simplexml_load_file( $soilFile );//Default file type

//loadFile(getcwd());
//loadUserFile($_SESSION['directory']);
function loadFile($path, $sxe)
{
    $XDBFile = $path . "/data/AgMIPWHAU.xdb.xml";
    $weatherFile = $path . "/data/ARBASXA1.wdb.xml";
    $cropFile = $path . "/data/crops.cdb.xml";
    $soilFile = $path . "/data/face.sdb.xml";
    $tipFile = $path . "/data/salus.ddb.xml";
    $gdbFile = $path . "/data/salusshort.gdb.xml";
    $testFile = $path . "/uploads/testNew.xml";
    if( isset($_POST['fileType']))
    {
        $requestedFileType = $_POST['fileType'];
        switch ($requestedFileType) {
            case 'weather':
                $sxe=simplexml_load_file( $weatherFile );
                break;
            case 'soil':
                $sxe=simplexml_load_file( $soilFile );
                break;
            case 'crop':
                $sxe=simplexml_load_file( $cropFile );
                break;
            case 'gdb':
                $sxe=simplexml_load_file( $gdbFile );
                break;
            case 'xdb':
                $sxe=simplexml_load_file( $XDBFile );
                break;

            default:
                break;
        }
    }
    dispFile( $sxe);
}
function loadUserFile($path)
{
    $XDBFile = $path . "/AgMIPWHAU.xdb.xml";
    $weatherFile = $path . "/ARBASXA1.wdb.xml";
    $cropFile = $path . "/crops.cdb.xml";
    $soilFile = $path . "/face.sdb.xml";
    $tipFile = $path . "/salus.ddb.xml";
    $gdbFile = $path . "/salusshort.gdb.xml";
    $sxe=simplexml_load_file( $soilFile );//Default file type
    if( isset($_POST['fileType']))
    {
        $requestedFileType = $_POST['fileType'];
        switch ($requestedFileType) {
            case 'weather':
                $sxe=simplexml_load_file( $weatherFile );
                break;
            case 'soil':
                $sxe=simplexml_load_file( $soilFile );
                echo $soilFile . "<br>";
                break;
            case 'crop':
                $sxe=simplexml_load_file( $cropFile );
                break;
            case 'gdb':
                $sxe=simplexml_load_file( $gdbFile );
                echo $gdbFile . "<br>";
                break;
            case 'xdb':
                $sxe=simplexml_load_file( $XDBFile );
                break;

            default:
                break;
        }
    }
    dispFile( $sxe);
}

//$sxe=simplexml_load_file( $weatherFile );
//dispFile( $sxe);


$endTime = new DateTime();
$diff = $endTime->diff($startTime);
print "Time to create: " . $diff->s . "seconds";
echo "<br><button onclick='createXml()'>Submit File</button>";

function dispFile( $sxe )
{
    $name = $sxe->getName();
    echo "<div class='fileType' id='$name'>File type: <font style='color:red'>";
    echo $sxe->getName() . "</font><br>";
    foreach ($sxe->children() as $child)
    {
        handleElement( $child ); 
    }
    echo "</div>";
}
function handleElement( $element )
{
    $numChildren = $element->count();
    switch($numChildren)
    {
        case 0:
            childlessElement($element);
            break;
        case ($numChildren > 0):
            elementWithChildren($element);
            break;
    }
}

// Handle elements with no children
function childlessElement($element)
{
    if( (count($element->attributes()) > 0 ) )
    {
        if( ((string) $element) != '' )
        {
            // Data stored as CSV table
            printdataAsCsvTable( $element );
        }
        else
        {
            //Element contains data in attributes
            $name = $element->getName();
            echo "<font style='color:blue'>" . $name . "</font><br>";
            printAttributeTable( $element );
            
        }
    }
    else
    {   // Can't determine if element is empty wrapper or stringData
        // Currently defaulting to empty stringData
        if( ((string) $element) == "" )
        {
            $name = $element->getName();
            echo "<div class='element' type='wrapper' name='$name' attributes='0'>"
                    . "<font style='color:blue'>".$name."</font><br>";
            echo "</$name>";
        }
        else
        {
        //Element contains a string of information
        printDataAsString( $element );
        }
    }
}
//Handle elements with children
function elementWithChildren($element)
{
    //echo "children<br>";
    $dataInChildren = lookForDataInChildren( $element );//Check for data in children
    switch($dataInChildren){
        case 0:
            noDataInChildren($element);
            break;
        case 1:
            dataInChildren( $element );
            break;
    }
}
function printAttributeTable( $element )
{
    
    $name = $element->getName();
    echo "<table class='attributeTable' type='attributeTable' name='$name' border=1><tr>";
    foreach($element->attributes() as $a => $b)
    {
        echo "<th id='heading'>" . $a . "</th>";
    }
    echo "</tr>";
    foreach($element->attributes() as $a => $b)
    {
        echo "<td class='element' type='attributeValue' id='$a'>"
                . "<input type='text' value='$b'></input></td>";
    }
    echo "</tr></table><br>";
}

function printUniformChildrenTable( $element )
{
    $tableId = $element->getName(); //to reference table from parent element in html
    $tableName = $element->children()->getName();
    echo "<table class='uniformDataTable' type='uniformDataTable' name='$tableName'"
            . " border=1><tr>";
    printTableHeadings( $element->children() );
    foreach ($element->children() as $child)
    {
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
function printTableHeadings( $element )
{
    echo "<font style='color:green'>" . $element->getName() . "</font><br>";
        foreach($element->attributes() as $a => $b)
         {
             echo "<th>" . $a . "</th>";
         }
}

/*
Elements can have:
	 only child elements
	 attributes with valuable information
	 attributes with valuable info and child elements
	 csv formatted body with an attribute that contains column names
*/

//XML Testing


function noDataInChildren( $element )
{
    $name = $element->getName();
    //echo "No child Data<br>";
    $attributes = lookForAttributes( $element );
    echo "<div class='element' type='wrapper' name='$name' attributes='$attributes'>"
            . "<font style='color:blue'>".$element->getName()."</font><br>";
    if($attributes == 1)
    {
        printAttributeTable($element);
    }
    //Pass first child of element to handleElement...recursive
    foreach ($element->children() as $child)
    {
    //echo "<font style='color:blue'>". $element->getName() . "</font><br>";
        handleElement( $child ); 
    }
    echo "</div>";
    
}
function dataInChildren( $element )
{
    //echo "looking for type of data in children<br>";
    $uniformChildData =  lookForUniformChildren( $element );
    switch($uniformChildData){
        case 0:
            //echo "uniform childData<br>";
            printUniformChildren($element);
            break;
        case 1:
            //echo "hetero child data<br>";
            printHeteroChildren($element);
            break;
    }
}

function lookForDataInChildren( $element )
{
    //echo "looking for data in children<br>";
    $dataInChildren = 1;  // Initally assume children contain data for parent
    foreach ($element->children() as $child)
    {
        // If a child has children then data is not in children
        if( $child->count() > 0)
        {
            $dataInChildren = 0;
            break;  // No need to check further...break from loop
        }
    }
    return $dataInChildren;
}
function lookForUniformChildren( $element )
{
    $uniformChildren = 0;    
    $name = $element->children()->getName();
    foreach ($element->children() as $child)
    {
        // If all children don't have same name then not uniform
        if( $name != $child->getName())
        {
            $uniformChildren = 1;
            break;  // No need to check further...break from loop
        }
    }
    return $uniformChildren;
}
function lookForAttributes( $element )
{
    $attributes = count($element->attributes());
    switch($attributes){
        case 0:
            return 0;
            break;
        case ($attributes>0):
            return 1;
            break;
    }
    
}
function printUniformChildren( $element )
{
    $name = $element->getName();
    $attributes = lookForAttributes( $element );
    echo "<div class='element' type='uniformChildData' name='$name' "
            . "attributes='$attributes'><br>";
    if($attributes == 1)
    {
        printAttributeTable($element);
    }
    echo "<font style='color:blue'>" . $name . "</font><br>";
    printUniformChildrenTable($element);
    echo "</div>";
    
}
function printHeteroChildren( $element )
{
    $name = $element->getName();
    $attributes = lookForAttributes( $element );
    echo "<div class='element' type='heteroChildData' name='$name' "
            . "attributes='$attributes'><br>";
    $attributes = lookForAttributes( $element );
    if( $attributes == 1)
    {
            echo "<font style='color:blue'>" . $name . "</font><br>";
            printAttributeTable($element);
    }
    foreach ($element->children() as $child)
    {
        handleElement($child);
    } 
    echo "</div>";
}
function printDataAsCsvTable( $element )
{
	$csv =  ((string) $element);
        $length = strlen($csv);
        $val = '';
        $i = 0;
	$numColumns;
        $tableName = $element->getName();
 	echo "<div class='element' type='csvData' name='$tableName'><br> "
                . "<span style='color:blue'>" . $element->getName() . "</span> ";
        echo "Data in CSV format<br>";
            echo "<table class='csvTable' id='csvTable' name='$tableName' "
                    . "border='1' border-collapse='collapse' cellpadding='5'><tr>";
            foreach($element->attributes() as $a => $b)
            {
                    $bArray = explode(",",$b);
                    $numColumns = count($bArray)-1;
                    foreach($bArray as $val)
                    {
                        if( $val != '')
                        {
                            echo "<th>" . $val . "</th>";
                        }
                    }
            echo "</tr><tr>";
            }
            //Create table from csv as string
            for($j=0; $j<$length; $j++)
            {
                if( $csv[$j] != ',')
                {
                    if( $csv[$j] == "\n" && $i!=0)//No more data for row 
                    {
                        //Fille rest of columns with empty inputs
                        while( $i<$numColumns )
                        {
                            echo "<td align='center'><input type='text' size='5'"
                            . " value=''></input></td>";
                            $i++;
                        }
                        echo "</tr><tr>";
                        $i=0;
                    }
                        $val .= $csv[$j];
                }
                else
                {
                    $i++;
                    $val = ltrim($val);
                    echo "<td align='center'><input type='text' size='5' "
                    . "value='$val'></input></td>";
                    //echo $val;
                    $val = '';
                }
            }
            echo "</tr></table></div><br>";
}
function printDataAsString( $element )
{
    $attributes = lookForAttributes( $element );
    $name = $element->getName();
    echo "<font style='color:blue'>" . $name . "</font><br>";
    echo "<div class='element' type='stringData' name='$name' attributes='$attributes'>"
            . "<textarea rows='3' cols='30' value='$element'>$element</textarea></div>";
}

function readOnlyDiv( $element )
{
}
        ?>
    </body>
</html>
