<?php
session_start();

 $file = $_SESSION['directory'] . "/"  . $_GET['fileToDownload'];

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

/*
function printUserFiles( $dir )
{
	echo "<div id='myFiles'><h3>Your current files</h3><form method='post' action='home.php'>";
	// Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) 
				{
                    if ($dh = opendir($dir)) 
					{
						while (($file = readdir($dh)) !== false) {
							if ($file != "." && $file != ".." && $file != ".htaccess") {
								echo "<input type='radio' name='fileToDownload' value=$file>" . 
								$file . "<br />";
							}
						}
						echo "<input type='submit' name='submit' value='Download'></form></div>"; // close list of user's files
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
}*/
?>