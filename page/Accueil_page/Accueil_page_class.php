<?php
	class Accueil_page extends Page
	{

		public function __construct()
		{
			parent::__construct();
			$this->name="Accueil";
		}


		public function getHead($sspage='',$param='')
		{
			$title = "Accueil - Music For Dancers";
			$description = "Crossover, votre outil de decouverte de série";
			$keywords = 'crossover, tvshows, betaseries, series, shows, actors';
			$canonical = 'http://musicfordancers.gaelcarre.fr';
			return parent::constructHead($title,$description,$keywords,$canonical);
		}

		public function Index($param = "")
		{

			traiterVisite(Site::getInstance());

			$user = Site::getInstance()->getUser();
			$template = __PAGE_DIR__.'Accueil_page/template/index.html';

			$news = Site::getInstance()->getDB()->Select("News","News_lang='".$_SESSION['lang']."'","*","ORDER BY News_date desc");
			$this->objSmarty->assign('news',$news);

			$html = $this->objSmarty->fetch($template);
			return $html;
		}

		public function Ask($param = "")
		{
			$template = __PAGE_DIR__.'Accueil_page/template/ask.html';
			$html = $this->objSmarty->fetch($template);
			return $html;
		}
	}
?>