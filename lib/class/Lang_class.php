<?php
class LANG
{

	static public function get($attr)
	{
		if(isset($_SESSION['lang']))
			$lng = $_SESSION['lang'];
		require(__LNG_DIR__.strtoupper($lng).'.php');
		if(isset($lang[$attr])) return $lang[$attr];
		else
		{
			return "error lng";
		} 
	}

	static public function GetArray()
	{
		if(isset($_SESSION['lang']))
			$lng = $_SESSION['lang'];
		require(__LNG_DIR__.strtoupper($lng).'.php');
		return $lang;
	}	
}
?>