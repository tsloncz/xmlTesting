<?php
	session_start();
	if(isset($_POST['fileToDownload']))
	{	
		$file = $_SESSION['directory'] . "/"  . $_POST['fileToDownload'];
		include_once('download.php');
		downloadFile( $file );
		printUserFiles( $_SESSION['directory'] );
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Salus-X...ml Pro Premium</title>
  <meta name="description" content="Description of your site goes here">
  <meta name="keywords" content="keyword1, keyword2, keyword3">
  <link href="css/style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="data/jquery-ui.css" />  
        <script type="text/javascript" src="data/jquery-1.9.1.js"></script>
        <script type="text/javascript" src="data/jquery-ui.js"></script>
        <script type="text/javascript" src="saveAndUpload.js"></script>
        <script type="text/javascript" src="utilities.js"></script>
</head>
<body>
<div id="main-wraper">
<div id="top-wraper">
<div id="banner">Salus-X...ml Pro Premium </div>
<div id="nav">
<ul>
  <li><a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>">Home</a></li>
  <li><a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>?cmd=upload">Upload files</a></li>
  <li><a href="<?php echo htmlentities($_SERVER['PHP_SELF']);?>?cmd=download">Download Files</a></li>
  <li><a href="#">Email</a></li>
  <li style="border: medium none ;"><a href="#">Links</a></li>
</ul>
</div>
</div>
<div id="mid-wraper">
<div class="mid-wraper-top">
<div class="mid-leftouter">
<div class="mid-left-container">
<!-- Begin Dynamic Content -->
<div class="Content">
<?php
  // create an array of allowed pages
  $allowedPages = array('index', 'fileInterface', 'upload', 'download');
  
  // check if the page variable is set and check if it is the array of allowed pages
  if(isset($_GET['cmd']) && in_array($_GET['cmd'], $allowedPages))
  {
    // first check that the file exists
    if(file_exists($_GET['cmd'].'.php'))
	{
		//  If all is well, we include the file
		include_once($_GET['cmd'].'.php');
	}
    else
	{
		// A little error message if the file does not exist
		echo 'No such file exists';
	}
	if($_GET['cmd'] == 'download')
	{
		echo "<h1>Download Files</h1>";
		printUserFiles( $_SESSION['directory'] );
	}
	if($_GET['cmd'] == 'fileInterface')
	{
		createSaveForm($_GET['file']);
		createFileInterface($_SESSION['directory'] . "/" . $_GET['file']);
	}

    }
  else if(isset($_GET['cmd']) )
    {
		// first check that the file exists
		if(file_exists($_GET['cmd'].'.php'))
		{
			//include the file
			$_SESSION['cmd'] = $_GET['cmd'];
			include_once($_GET['cmd'].'.php');
		}
    }
	/*else if(isset($_POST['fileToDownload']))
	{	
		$file = $_SESSION['directory'] . "/"  . $_POST['fileToDownload'];
		include_once('download.php');
		downloadFile( $file );
	}*/
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
</div>
<div class="mid-left-container" style="margin-top: 10px;">
<div class="inner-left">
<h2>About <span class="yellow-heading">us</span></h2>
<div style="border-right: 1px solid rgb(153, 153, 153); width: 294px; float: left; padding-right: 12px;">
<p>Lorem Ipsum is simply dummy text of the printing and typesetting
industry. Lorem Ipsum has been the industry's standard dummy text ever
since the 1500s, when an unknown printer took.<br>
<br>
</p>
<p>Galley of type and scrambled it to make a type specimen book. It has
survived not only five centuries, but also the leap into electronic
typesetting, remaining.<br>
<br>
</p>
<strong><a href="#" class="read-more">read more...</a></strong></div>
</div>
<div class="inner-right">
<h2>Recent <span class="yellow-heading">Articles</span></h2>
<div style="width: 290px; float: left; padding-left: 8px;">There are
many variations of passages of Lorem Ipsum available, but the majority
have suffered alteration in some form, by injected humour, or
randomised words which don't look even slightly believable. If you are
going to use a passage of Lorem Ipsum, you need to be sure there isn't
anything embarrassing hidden in the middle of text. you need to be sure
there isn't.<br>
<br>
<strong><a href="#" class="read-more">read more...</a></strong></div>
</div>
</div>
</div>
<!--
<div class="right-container">
<div class="right-container-top">
<h3>Categ<span class="yellow-heading">ories</span></h3>
<ul>
  <li><a href="#">Lorem Ipsum is simply dummy</a></li>
  <li><a href="#">Lorem Ipsum is</a></li>
  <li><a href="#">Lorem Ipsum</a></li>
  <li><a href="#">Lorem Ipsum is simply dummy</a></li>
  <li><a href="#">Lorem Ipsum is simply</a></li>
  <li><a href="#">Lorem Ipsum is simply </a></li>
  <li><a href="#">Lorem Ipsum is simply dummy</a></li>
  <li><a href="#">Lorem Ipsum is</a></li>
</ul>
</div>
<div class="right-container-dwn">
<h4>News</h4>
<strong>15.04.2010</strong> Lorem Ipsum is simply dummy text of the
printing and typesetting industry. Lorem Ipsum has been. <br>
<br>
<strong>15.04.2010</strong> Lorem Ipsum is simply dummy text of the
printing and typesetting industry. Lorem Ipsum has been.<br>
<br>
<strong><a href="#" class="read-more-right">read more...</a></strong> </div>
</div>
</div>
<div class="mid-wraper-bttm"><img src="images/mid-bttm.jpg" alt=""></div>
</div> -->
<div class="footer">
<div class="footer-side"><a href="#" class="footer-link">Home</a> <a href="#" class="footer-link">About us</a> <a href="#" class="footer-link">Recent articles</a> <a href="#" class="footer-link">Email</a> <a href="#" class="footer-link">Resources</a>
<a href="#" class="footer-link">Links</a></div>

<!--DO NOT Remove The Footer Links-->
<!--Designed by--><a href="http://www.htmltemplates.net">
<img src="images/footnote.gif" class="copyright" alt="html templates"></a>
<div class="footer-right">&copy; Copyright 2014. Designed by <a class="footer-link" target="_blank" href="http://www.htmltemplates.net/">htmltemplates.net</a>
<!--DO NOT Remove The Footer Links-->

</div>
</div>
</div>

</body></html>