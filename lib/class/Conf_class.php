<?php
	class Conf
	{

		private $confs = null;
		public function __construct()
		{
			$this->confs = simplexml_load_file(__CONF_DIR__."conf.xml");
		}

		public function getConf($id)
		{
			foreach($this->confs as $conf)
			{
				if($conf['id'] == $id)
				{
					return "{$conf->value}";
				}
					
			}
			return null;
		}
	}
?>