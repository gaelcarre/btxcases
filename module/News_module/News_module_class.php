<?php
	class News_module extends Module
	{

		public function __construct()
		{
			parent::__construct();
			$this->name = "News";
		}

		

		public function getHead($page='',$param='')
		{
			return parent::constructHead('Accueil-localhost');
			
		}

		public function Default_action($param = '')
		{
			if($param != '' and isset($param['id']))
			{
				$data = Site::getInstance()->getDB()->Select('news',"news_id = $param");
				$this->objSmarty->assign("news",$data->fetchAll());
				$template = __MOD_DIR__.'News_module/template/display_one.html';
			}
			else
			{
				$nb = isset($param['nb'])?$param['nb']:5;
				$template = isset($param['template'])?$param['template']:'display';
				$data = Site::getInstance()->getDB()->Select('news',"","","ORDER BY news_created_at DESC LIMIT $nb");
				$datas = array();
				foreach($data as $row)
				{
					$resume = seeMore("index.php?module=news&page=display&param=".$row['news_id'],100,$row['news_content'],"lire la suite");
					$row['news_content'] = $resume;
					$datas[$row['news_id']] = $row;

				}
				$this->objSmarty->assign("news",$datas);
				$template = __MOD_DIR__.'News_module/template/'.$template.'.html';
			}

			$html = $this->objSmarty->fetch($template);
			return $html;
		}

		public function Slider_action($param = 0)
		{
			$data = Site::getInstance()->getDB()->Select('news',"","","ORDER BY news_created_at DESC LIMIT $param");
			$datas = array();
				foreach($data as $row)
				{
					$resume = seeMore("index.php?module=news&page=display&param=".$row['news_id'],100,$row['news_content'],"lire la suite");
					$row['news_content'] = $resume;
					$datas[$row['news_id']] = $row;

				}
			$this->objSmarty->assign("news",$datas);
			$template = __MOD_DIR__.'News_module/template/slider.html';
			$html = $this->objSmarty->fetch($template);
			return $html;

		}
	}
?>