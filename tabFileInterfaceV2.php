<?php
/*
 * V2
*/
session_start();

/* 
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
function createFileInterface($filePath)
{
    //$sxe=simplexml_load_file( $filePath );//Default file type
    if (file_exists($filePath)) 
    {
        $xml = simplexml_load_file($filePath);
        createSaveForm($filePath);
        dispFile( $xml);
    }
    else 
    {
        exit('Failed to open' . $filePath);
    }
}

//$sxe=simplexml_load_file( $weatherFile );
//dispFile( $sxe);


$endTime = new DateTime();
$diff = $endTime->diff($startTime);
//print "Time to create: " . $diff->s . "seconds";
function createSaveForm($filename)
{
	$filename = stripName($filename);
	echo "<br><button onclick='createXml()'>Save File As</button>";
	echo "&nbsp<input type='text' id='fileName' onChange='validFileName()' value='$filename'>";
	echo "<span id='validFile'></span><br />";
}
function stripName($name)
{
	$name = explode('/',$name);
	return implode(explode('.',end($name), -2));
}
function dispFile( $sxe )
{
    $name = $sxe->getName();
    echo "<div class='fileType' id='$name'>File type: <font style='color:red'>";
    echo $sxe->getName() . "</font><br>";
	createTabs($sxe);
    echo "</div>";
}
function handleElement( $element )
{
    $numChildren = $element->count();
    switch($numChildren)
    {
        case 0:
            if( (count($element->attributes()) > 0 ) )
			{
				if($element->getName() == "Weather")
				{
					printDataAsCsv( $element );
				}
				else
				{
					printAttributes( $element );
				}
			}
			else
			{
            	printData( $element );
			}
            break;
        case ($numChildren > 0):
			createTabs( $element);
            //elementWithChildren($index,$element);
            break;
    }
}
function printData( $element )
{
	$name = $element->getName();
	if( $name == "Version" || $name == "ReleaseDate" || $name =="Notes")
	{
		echo "<div class='elementData'>"
            . "<textarea rows='3' cols='30' readonly>" . $element . "</textarea></div>";
	}
	else
	{
		echo "<div class='elementData'>"
            . "<textarea rows='3' cols='30'>" . $element . "</textarea></div>";
	}
}
function printAttributes( $element )
{
	//Elements that can user can create. Reference salusClasses google doc
    $NCardinalityDictionary = array("Species","Phase","Factor","Cultivar","PhotSynType","Point",
										"Crop_Par","Fert_Par","Irr_Par","Residue_Par","Till_Par",
										"C)2_Trend_XY","Soil_Bio_Kinectic_Par","Stations",
										"Storm_Intensity","Hourly_Rainfall","Soil","Layer",
										"Experiment","Rotation_Components","Component",
										"Mgt_Chemical_App","Mgt_EncMod_App","Mgt_Harvest_App",
										"Mgt_Tillage_App","Mgt_Fertilizer_App","Mgt_Residu_App",
										"Mgt_Irrigation_App");
    $name = $element->getName();
    //echo "<table class='attributeTable' type='attributeTable' name='$name' border=1><tr>";
	echo "<div class='attributes'>";
	//If version make readonly
	if( $name == "Version")
	{
		foreach($element->attributes() as $a => $b)
		{
			echo "<div class='attribute' id='$a'><b>$a</b><br /><input type='text' value='$b' readonly></input></div>";
    	}
	}
	else 
	{
		if( in_array($name,$NCardinalityDictionary) )
		{
			echo "<button onClick='newTab( $(this).parent() )'>New " . $name . "</button>";
			echo "<button onClick='clearValues( $(this).parent() )'>Clear Values</button><br />";
		}
		foreach($element->attributes() as $a => $b)
		{
			echo "<div class='attribute' id='$a'><b>$a</b><br /><input type='text' value='$b'></input></div>";
		}
	}
	echo "</div><br />";
}
function createTabs($element)
{
    if( (count($element->attributes()) > 0 ) )
    {
		printAttributes( $element );
	}
    echo "<div id='tabs'><ul>";
    $i = 0;
    foreach ($element->children() as $index=>$child)
    {
		$uniqueId = getUniqueIdentifier($child);
        $name = $child->getName();
		if($uniqueId != "")
		{
        	echo "<li><a href='#" . $name . "_" . $i . "'>" . $name . $uniqueId . "</a><span class='ui-icon ui-icon-close' onclick='removeTab($(this))' role='presentation'>Remove Tab</span></li>";
		}
		else
		{
			echo "<li><a href='#" . $name . "_" . $i . "'>" . $name . "</a></li>";
		}
        $i++;
    }
    echo "</ul>";
	$i = 0;
	foreach ($element->children() as $child)
    {
		$id = $child->getName() . "_" . $i;
		$name = $child->getName();
        echo "<div class='element'  id='$id'  name='$name'>";
		handleElement($child);
		echo "</div>";
			$i++;
    }
	echo "</div>";
}
function getUniqueIdentifier($element)
{
	$uniqueIdentifierDictionary = array("Species"=>"Species","Phase"=>"Label","Factor"=>"TRF",
								"Cultivar"=>"CultivarID","PhotoSynType"=>"PhotSynID","Point"=>"CO2",
								"Version"=>"Number","Crop_Par"=>"Name","Fert_Par"=>"Name",
								"Irr_Par"=>"Name","Residue_Par"=>"Name","Till_Par"=>"Name",
								"CO2_Trend_XY"=>"Yr","Soil_Bio_Kinetic_Par"=>"Element",
								"Stations"=>"Station_Name","Storm_Intensity"=>"Month",
								"Hourly_Rainfall"=>"Year-DOY-Hr","Soil"=>"SlDesc",
								"Layer"=>"Current ZLYR","Experiment"=>"Title","Component"=>"Title",
								"Mgt_Chemical_App"=>"Year-DOY","Mgt_EnvMod_App"=>"Year-DOY",
								"Mgt_Harvest_App"=>"Year-DOY","Mgt_Tillage_App"=>"Year-DOY",
								"Mgt_Fertilizer_App"=>"Year-DOY","Mgt_Residue_App"=>"Year-DOY",
								"Mgt_Irrigation_App"=>"Year-DOY");
	//Needed to handles elements with same name but differnt ids
	$DuplicateUniqueIdentifierDictionary = array("Species"=>"Species_Name","Layer"=>"ZLYR");
	$name = $element->getName();
	$temp = '';
	$uniqueId = '';
    foreach($uniqueIdentifierDictionary as $a => $b)
    {
		if($a == $name)
		{
			$temp = $b;
		}
	}
    foreach($element->attributes() as $a => $b)
    {
		$found = strpos($a , $temp);
		//If a contains uniqueId
		if( $a == $temp )
		{
			$uniqueId = "_" . $b;
			break;
		}
	}
	if($uniqueId == '')
	{
		foreach($DuplicateUniqueIdentifierDictionary as $a => $b)
		{
			if($a == $name)
			{
				$temp = $b;
			}
		}
		foreach($element->attributes() as $a => $b)
		{
			$found = strpos($a , $temp);
			//If a contains uniqueId
			if( $a == $temp )
			{
				$uniqueId = "_" . $b;
				break;
			}
		}
	}
	return $uniqueId;
}

function createTabList( $element )
{
    echo "<div id='tabs'><ul>";
    $i = 0;
    foreach ($element->children() as $index=>$child)
    {
        $name = $child->getName();
        echo "<li><a href='#" . $name . $i . "'>" . $name . "</a></li>";
        $i++;
    }
    echo "</ul>";
}
function printTableHeadings( $element )
{
    echo "<font style='color:green'>" . $element->getName() . "</font><br>";
        foreach($element->attributes() as $a => $b)
         {
             echo "<th>" . $a . "</th>";
         }
}
//Replaces printDataAsCsvTable
function printDataAsCsv( $element )
{
	$csv =  ((string) $element);
        $length = strlen($csv);
        $val = '';
        $i = 0;
	$numColumns;
        $tableName = $element->getName();
	//print locked position headers
	
		echo "<div class='headers'>";
		foreach($element->attributes() as $a => $b)
		{
				$bArray = explode(",",$b);
				$numColumns = count($bArray)-1;
				foreach($bArray as $val)
				{
					if( $val != '')
					{
						echo "<div class='heading'>" . $val . "</div>";
					}
				}
		echo "</div><br />";
		}
            echo "<div class='values'><div class='valuesRow'>";
            //Create table from csv as string
            for($j=0; $j<$length; $j++)
            {
                if( $csv[$j] != ',')
                {
                    if( $csv[$j] == "\n" && $i!=0)//No more data for row 
                    {
                        //Fill rest of columns with empty inputs
                        while( $i<$numColumns )
                        {
                            echo "<span class='value'><input type='text' size='6'"
                            . " value=''></input></span>";
                            $i++;
                        }
                        echo "<br /></div><div class='valuesRow'>";
                        $i=0;
                    }
                    $val .= $csv[$j];
                }
                else
                {
                    $i++;
                    $val = ltrim($val);
                    echo "<span class='value'><input type='text' size='6' "
                    . "value='$val'></input></span>";
                    //echo $val;
                    $val = '';
                }
            }
            echo "</div></div><br>";
}
function printDataAsString( $element )
{
    $attributes = lookForAttributes( $element );
    $name = $element->getName();
    echo "<font style='color:blue'>" . $name . "</font><br>";
    echo "<div class='element' id='$name' type='stringData' name='$name' attributes='$attributes'>"
            . "<textarea rows='3' cols='30' value='$element'>$element</textarea></div>";
}

 ?>
