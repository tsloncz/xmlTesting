<?php 
//Runs simulation
function runSimulation($userPath)
{
	//Get files user has statged to run simulation with
	$simulationDir = $userPath . "simulation/";
	if ($dh = opendir($simulationDir)) 
	{
		$exeString = "weather='wdb' soil='sdb' experiment='xdb' crop='cdb' global='gdb'";
		$replaceString = $exeString;
		//Copy executable into simulation directory
		if( !copy("../SALUS_C", $simulationDir) )
		{
			$errors= error_get_last();
			echo "COPY ERROR: ".$errors['type'];
			echo "<br />\n".$errors['message'];
		} 
		while (($file = readdir($dh)) !== false) 
		{
			$type = explode(".", $file);
			$typeElement = count($type) - 2;
			$fileType = $type[$typeElement];
			$replaceString = str_replace($fileType,$file,$replaceString);
		}
		echo $replaceString;
	}
}
?>