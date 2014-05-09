<?php
session_start();
echo "<h1>Uplaod Files</h1>";
function oldUpload(){
$allowedExts = array("xml");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "text/xml"))
&& ($_FILES["file"]["size"] < 200000)
&& in_array($extension, $allowedExts))
  {
    if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

    if (file_exists("uploads/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "uploads/" . $_FILES["file"]["name"]);
      echo "Stored in: " . "uploads/" . $_FILES["file"]["name"];
        $sxe=simplexml_load_file( "uploads/" . $_FILES["file"]["name"] );

        echo "file type: <font color='red'>" . $sxe->getName() . "</font><br>";
        foreach ($sxe->children() as $child)
          {
                 handleElement( $child );
          }
      }
    }
   }
else
  {
    echo "Invalid file";
  }
}//end oldUpload
  
function uploadFile( $dir )
{
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
			echo "Upload: " . $_FILES["file"]["name"] . "<br>";
			echo "Type: " . $_FILES["file"]["type"] . "<br>";
			echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
			echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
		
			if (file_exists($dir . $_FILES["file"]["name"]))
		  	{
		  		echo $_FILES["file"]["name"] . " already exists. ";
		  	}
			else
			{
			  	move_uploaded_file($_FILES["file"]["tmp_name"],
			  	"uploads/" . $_FILES["file"]["name"]);
			  	echo "Stored in: " . "uploads/" . $_FILES["file"]["name"];
				$sxe=simplexml_load_file( "uploads/" . $_FILES["file"]["name"] );
			}
		}
   	}
	else
  	{
    	echo "Invalid file";
  	}
}

function checkFileType( $fileName )
{
	$validExtensions= array('gdb.xml','cdb.xml','sdb.xml','wdb.xml');
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	if ( (($_FILES["file"]["type"] == "text/xml")) 
			&& ($_FILES["file"]["size"] < 200000)
			&& in_array($extension, $allowedExts) )
  	{
	}
}
?>
<form action="index.php" method="post" accept-charset=""enctype="multipart/form-data">
            <label for="file">Filename:</label>
            <input type="file" name="file" id="file"><br>
            <button type="submit" name="uploadFile">Upload a file</button>
        </form>