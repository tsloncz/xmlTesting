<?php 
//Runs simulation
function runSimulation($userPath)
{
	//Get files user has statged to run simulation with
	$simulationDir = $userPath . "simulation/";
	if ($dh = opendir($simulationDir)) 
	{
		$exeString = './SALUS_C -wn xdb="[xdb]" cdb="[cdb]" sdb="[sdb]" gdb="[gdb]" resdir="[resdir]" file1="daily_osx.csv" freq1="1" vars1="GWAD,CWAD,ETAC,SRAA,LAI,gPhase,NitroFac" file2="seasonal_osx.csv" freq2="season" vars2="GWAD,CWAD,ETAC,SRAA,LAI,gPhase,NitroFac" msglevel="debug"';
		//$exeString = "weather='wdb' soil='sdb' experiment='xdb' crop='cdb' global='gdb'";
		$replaceString = $exeString;
		$replaceString = str_replace("[resdir]",$userPath,$exeString);
		//Copy executable into simulation directory
		/*if( !copy("../executable/SALUS_C", $simulationDir) )
		{
			$errors= error_get_last();
			echo "COPY ERROR: ".$errors['type'];
			echo "<br />\n".$errors['message'];
		} */
		while (($file = readdir($dh)) !== false) 
		{
			$type = explode(".", $file);
			$typeElement = count($type) - 2;
			$fileType = $type[$typeElement];
			$fileType = "[" . $fileType . "]";
			$file = $simulationDir . $file;
			$replaceString = str_replace($fileType,$file,$replaceString);
		}
		echo $replaceString;
		echo exec($exeString);
	}
}
?>
