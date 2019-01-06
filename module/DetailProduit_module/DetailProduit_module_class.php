<?php
	class DetailProduit_module extends Module
	{

		public function __construct()
		{
			parent::__construct();
			$this->name = "DetailProduit";
		}

		

		public function getHead($page='',$param='')
		{
			return parent::constructHead('DetailProduit-localhost');
			
		}

		public function Default_action($param = '')
		{
			$template = __MOD_DIR__.'DetailProduit_module/template/'.$param['template'].'.html';
			if(isset($param['filtre']))
				$data = Site::getInstance()->getDB()->Select($param['table'],$param['filtre']);
			else
				$data = Site::getInstance()->getDB()->Select($param['table']);
			$this->objSmarty->assign("datas",$data);

			$html = $this->objSmarty->fetch($template);
			return $html;
		}


	}
?>