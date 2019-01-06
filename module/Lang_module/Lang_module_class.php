<?php
class Lang_module extends Module
{
		public function __construct()
		{
			parent::__construct();
			$this->name = "Lang";
		}

		

		public function getHead($page='',$param='')
		{
			return parent::constructHead('Accueil-localhost');
			
		}

		public function Default_action($param = "")
		{
			$template = __MOD_DIR__.'Lang_module/template/lang.html';
			$html = $this->objSmarty->fetch($template);
			return $html;
		}

		public function Change_action($param = "")
		{
			if($param == "")
				$param = "FR";
			$param = strtoupper($param);
			$path = __LNG_DIR__.$param.'.php';
			
			if(file_exists($path))
			{
				$_SESSION['lang'] = strtoupper($param);				
			}

			redirect("http://www.musicfordancers.gaelcarre.fr");

		}
}
?>