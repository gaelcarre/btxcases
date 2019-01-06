<?php
	class Error404_module extends Module
	{

		public function __construct()
		{
			parent::__construct();
			$this->name = "Error404";
		}

		

		public function getHead($page='',$param='')
		{
			return parent::constructHead('Accueil-localhost');
			
		}
	}
?>