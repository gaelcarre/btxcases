<?php
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);


	header('Content-Type: text/html; charset=UTF-8');
	require("init.inc.php");
	require(__UTIL_DIR__."function.php");
	require(__UTIL_DIR__."load.inc.php");
	$site = Site::getInstance();
	if(isset($_GET['ajaxRequest'])) $_POST['ajaxRequest'] = $_GET['ajaxRequest'];
	if(isset($_POST['ajaxRequest']) and $_POST['ajaxRequest'] == 1)
	{
		$site->requestAjax();

	}

?>
<?php $site->loadContent();?>
