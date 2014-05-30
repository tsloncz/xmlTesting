<?php
session_start();

if(isset($_POST['fileToDownload']))
{	
	$file = $_SESSION['directory'] . "/"  . $_POST['fileToDownload'];
	if (file_exists($file)) {
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
	else
	{
		echo "Failed to open file<br />";
	}
fclose ($fd);
exit;
}
else if(isset($_POST['uploadFile']))
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
function authenticate() {
    header("Location: http://salusmodel.glg.msu.edu/salusInterface/xmlTesting/");
    exit;
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
    }    
	else if(isset($_SESSION['userName']))
    {
		//printWelcome();
        //echo getcwd() . "<br>";
        $dir = $_SESSION['directory'];
        //echo $dir . "<br>";
        //uploadFile();
		//printUserFiles( $_SESSION['directory'] );
    }
    else if( isset($_SERVER['PHP_AUTH_USER']) )
    {
        $userName = $_SERVER['PHP_AUTH_USER'];
        $_SESSION['userName'] = $userName;
        $_SESSION['directory'] = getcwd() . "/" . $userName . "/";
		//printWelcome();
        //uploadFile();
		//printUserFiles( $_SESSION['directory'] );
    }

if( isset($_POST["cmd"]) && $_POST["cmd"] == "printUserFiles" )
{
    printUserFiles($_SESSION['directory']);
}
if( isset($_POST["cmd"]) && $_POST["cmd"] == "downloadFile" )
{
	$fileName = $_POST["fileToDownload"];
	$dir = $_SESSION['directory'] . "/";
	$file = $dir . $fileName;
	echo "Dir " . $file;
	if (file_exists($file)) {
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
	else
	{
		echo "Failed to open file<br />";
	}
fclose ($fd);
exit;
}
if( isset($_POST["cmd"]) && $_POST["cmd"] == "createInterface" )
{
    include 'tabFileInterfaceV2.php';
    $filePath = $_SESSION['directory'] . "/" . $_POST["filename"];
    echo createFileInterface($filePath);
}
if( isset($_POST["cmd"]) && $_POST["cmd"] == "addToSimulation" )
{
    $filePath = $_SESSION['directory'];
	$file = $_POST["fileToAdd"];
    addToSimulation($filePath, $file);
}
if( isset($_POST["cmd"]) && $_POST["cmd"] == "deleteFile" )
{
    $filePath = $_SESSION['directory'] . $_POST["fileName"];
    unlink($filePath);
	echo "deleted " . $filePath;
}
if( isset($_POST["cmd"]) && $_POST["cmd"] == "saveFile" )
{
    $userName = $_SERVER['PHP_AUTH_USER'];
	$uploadType = 'file';
	$xmlString = $_POST["xmlString"];
	$uploadType = $_POST["uploadType"];
	$fileName = $_POST["fileName"];
	$dir = $_SESSION['directory'] . "/";
	
	$file = fopen($dir . $fileName,"w");
	echo "Dir: " . $dir . $fileName;
	echo fwrite($file,$xmlString);
	fclose($file);
}
// $dir is path to users folder
function printUserFiles( $dir )
{
    // User's directory, and proceed to read its contents
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) 
        {
            while (($file = readdir($dh)) !== false) 
            {
				if ($file != "." && $file != ".." && $file != "deleted" && $file != ".htaccess") 
				{
					$userFiles[] = $file;
				}
            }
            closedir($dh);
			$files[] = array("userFiles"=>$userFiles);
			$simulationDir = $dir . "simulation/";
        if ($dh = opendir($simulationDir)) 
        {
            while (($file = readdir($dh)) !== false) 
            {
				if ($file != "." && $file != ".." && $file != "deleted" && $file != ".htaccess") 
				{
					$userSimulationFiles[] = $file;
				}
            }
			$files[] = array("userSimulationFiles"=>$userSimulationFiles);
            $files = json_encode($files);
            echo $files;
        }
		else
		{
			echo "Sorry,open directory failed<br>";
		}
		}
    }
    else
    {
        echo "Sorry,open directory failed<br>";
    }
}
function addToSimulation( $dir, $file )
{
	$filePath = $dir . $file;
	$simulationPath = $dir . "simulation/" . $file;
	if( !copy($filePath, $simulationPath) )
	{
		$errors= error_get_last();
		echo "COPY ERROR: ".$errors['type'];
		echo "<br />\n".$errors['message'];
	} 
}


?>
