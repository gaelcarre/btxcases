<?php
	class Language_page extends Page
	{

		public function __construct()
		{
			parent::__construct();
			$this->name="Language";
		}


		public function getHead($sspage='',$param='')
		{
			$title = "";
			$description = "";
			$keywords = '';
			$canonical = '';
			return parent::constructHead($title,$description,$keywords,$canonical);
		}

		public function Index($param = "")
		{
			return '';
		}

		public function Change($param = "")
		{
			if($param != ""){
				$_SESSION['lang'] = strtoupper($param);
			}
			redirect("/");
			
		}
	}
?>