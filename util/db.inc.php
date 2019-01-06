<?php
	//$db=mysql_connect('db408101490.db.1and1.com', 'dbo408101490', 's4S08Y3');
	//mysql_select_db('db408101490', $db);
	$db = new PDO('mysql:host=localhost;dbname=squad','root','', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
?>