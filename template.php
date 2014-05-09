<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" /> 
<!-- Based on a template by Ben Meadowcroft, see http://www.benmeadowcroft.com/webdev/  for more info-->
<title>Salus-X...ml pro</title>

</head>
<body>

<div class="title">
	<h1>Salus-X...ml Pro Premium</h1>
</div>
<br />
<br />
<div id='header'>
	<ul>
    	<a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">Homepage</a> |
        <a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>?page=upload">Upload Files</a> |
        <a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>?page=download">Download Files</a> |
        <a href="frame4.html" target="fill_me">Frame 4</a> |
        <a href="frame5.html" target="fill_me">Frame 5</a> 
	</ul>
</div>

<!-- Begin Dynamic Content -->
<div class="Content">
<?php
  // create an array of allowed pages
  $allowedPages = array('index', 'fileInterface', 'page3', 'page4');
  
  // check if the page variable is set and check if it is the array of allowed pages
  if(isset($_GET['page']) && in_array($_GET['page'], $allowedPages))
    {
    // first check that the file exists
    if(file_exists($_GET['page'].'.php'))
        {
        //  If all is well, we include the file
        include_once($_GET['page'].'.php');
        }
    else
        {
        // A little error message if the file does not exist
        echo 'No such file exists';
        }

    }
  else if(isset($_GET['file']) )
    {
    // first check that the file exists
    if(file_exists($_GET['page'].'.php'))
        {
        	//include the file
			$_SESSION['page'] = $_GET['file'];
        	include_once('fileInterface.php');
		}

    }
  // if somebody typed in an incorrect url
  else
    {
    // if things are not as they should be, we included the default page
    if(file_exists('index.php'))
        {
        // include the default page 
        include_once('index.php');
        }
    else
        {
        // if the default page is missing, we have a problem and it needs to be fixed.
        echo 'index.php is missing. Please fix me.';
        }
    }

?>

</div>
<!-- End Dynamic Content -->

<!-- Begin sidebar Content -->
<div class="sidebar" id="sidebar">
<ul class="menu">
<li>Templates</li>
<li><a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">Home</a></li>
<li><a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>?page=index">Page Index</a></li>
<li><a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>?page=page2">Page Two</a></li>
<li><a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>?page=page3">Page Three</a></li>
<li><a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>?page=page4">Page Four</a></li>
<li>&nbsp;</li>
<li><a href="http://www.phpro.org">Link</a></li>
<li><a href="http://www.phpro.org">Link</a></li>
<li><a href="http://www.phpro.org">Link</a></li>
<li>&nbsp;</li>
<li><a href="http://www.phpro.org">Link</a></li>
<li><a href="http://www.phpro.org">Link</a></li>
<li><a href="http://www.phpro.org">Link</a></li>
</ul>

  <div class="other">
  <form action="template.php" method="post" accept-charset=""enctype="multipart/form-data">
        <label for="file">upload file</label>
        <input type="file" name="file" id="file"><br>
        <button type="submit" name="uploadFile">Upload a file</button>
    </form>
  </div>
  <div class="other">
  <form action="template.php" method="post" accept-charset=""enctype="multipart/form-data">
        <label for="file">download file</label>
        <input type="file" name="file" id="file"><br>
        <button type="submit" name="downloadFile">Download a file</button>
    </form>
  </div>

</div>
<!-- End sidebar Content -->

</body>
</html>