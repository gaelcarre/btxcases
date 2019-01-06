<?php
	class Commande_page extends Page
	{

		public function __construct()
		{
			parent::__construct();
			$this->name="Commande";
		}


		public function getHead($sspage='',$param='')
		{
			$title = "Commande";
			$description = '';
			$keywords = '';
			$canonical = 'http://btxcases.gaelcarre.fr';
			return parent::constructHead($title,$description,$keywords,$canonical);
		}

		public function Index($param = "")
		{
			$template = __PAGE_DIR__.'Commande_page/template/index.html';

			$html = $this->objSmarty->fetch($template);
			return $html;
		}
	}
?>
