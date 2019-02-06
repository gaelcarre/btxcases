<?php
	class Utilisateur_page extends Page
	{

		public function __construct()
		{
			parent::__construct();
			$this->name="Utilisateur";
		}


		public function getHead($sspage='',$param='')
		{
			$title = "Utilisateur";
			$description = '';
			$keywords = '';
			$canonical = 'http://btxcases.gaelcarre.fr';
			return parent::constructHead($title,$description,$keywords,$canonical);
		}

		public function Connexion($param = "")
		{
			$template = __PAGE_DIR__.'Utilisateur_page/template/connexion.html';
			

			$html = $this->objSmarty->fetch($template);
			return $html;
		}
	}
?>
