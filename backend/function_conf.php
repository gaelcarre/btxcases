<?php
	function getConf($id)
	{
		$file  = simplexml_load_file("../conf/conf.xml");
		foreach ($file as $conf) {
			if($conf['id'] == $id)
				return "{$conf->value}";
		}

		return "";
	}
?>