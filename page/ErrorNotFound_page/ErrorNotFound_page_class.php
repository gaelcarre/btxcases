<?php
	class ErrorNotFound_page extends Page
	{

		public function __construct()
		{
			parent::__construct();
			$this->name="ErrorNotFound";
		}


		public function getHead($sspage='',$param='')
		{
			$title = "Page not found";
			$description = "";
			$keywords = '';
			$canonical = '';
			return parent::constructHead($title,$description,$keywords,$canonical);
		}

		public function Index($param = "")
		{
			$template = __PAGE_DIR__.'ErrorNotFound_page/template/index.html';
			$html = $this->objSmarty->fetch($template);
			return $html;
		}

	}
?>