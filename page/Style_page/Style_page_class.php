<?php
	class Style_page extends Page
	{

		private $style = '';

		public function __construct()
		{
			parent::__construct();
			$this->name="Style";
		}


		public function getHead($sspage='',$param='')
		{
			$title = $this->style." - Music For Dancers";
			$description = "Crossover, votre outil de decouverte de série";
			$keywords = 'crossover, tvshows, betaseries, series, shows, actors';
			$canonical = 'http://musicfordancers.gaelcarre.fr';
			return parent::constructHead($title,$description,$keywords,$canonical);
		}

		public function Index($param = "")
		{
			redirect("/");
		}

		public function Consult($param = "")
		{
			$template = __PAGE_DIR__.'Style_page/template/consult.html';

			$style= Site::getInstance()->getDB()->SelectFirst("Category", "Category_url = '".strtolower($param)."'");
			$styleTitle = $style['Category_title'];
			$this->objSmarty->assign("title", $styleTitle);
			$styleImage = $style['Category_image'];
			$this->objSmarty->assign("image", $styleImage);
			$this->style = $styleTitle;
			$style_id = $style['Category_id'];
			$style = Site::getInstance()->getDB()->SelectFirst("Category, CategoryDescription",
				"Category_lang='".$_SESSION['lang']."' and 
				 Category_url = '".strtolower($param)."' and Category_id = Cat_id");
			

			$cds = Site::getInstance()->getDB()->Select("CD,CDDescription", "CD_lang='".$_SESSION['lang']."' and
				CD_id = CDD_cdid and category = ".$style_id);
			$this->objSmarty->assign('style',$style);
			$this->objSmarty->assign('cds',$cds);

			$html = $this->objSmarty->fetch($template);
			return $html;
		}
	}
?>