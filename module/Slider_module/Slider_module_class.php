<?php
	class Slider_module extends Module
	{

		public function __construct()
		{
			parent::__construct();
			$this->name = "Slider";
		}

		

		public function getHead($page='',$param='')
		{
			return parent::constructHead('Accueil-localhost');
			
		}

		public function Default_action($param = '')
		{
			if($param != '')
			{
				$data = Site::getInstance()->getDB()->Select('news',"news_id = $param");
				$this->objSmarty->assign("news",$data->fetchAll());
				$template = __MOD_DIR__.'News_module/template/display_one.html';
			}
			else
			{
				$data = Site::getInstance()->getDB()->Select('news',"","","ORDER BY news_created_at DESC LIMIT 5");
				$datas = array();
				foreach($data as $row)
				{
					$resume = seeMore("index.php?module=news&page=display&param=".$row['news_id'],100,$row['news_content'],"lire la suite");
					$row['news_content'] = $resume;
					$datas[$row['news_id']] = $row;

				}
				$this->objSmarty->assign("news",$datas);
				$template = __MOD_DIR__.'News_module/template/display.html';
			}

			$html = $this->objSmarty->fetch($template);
			return $html;
		}

		public function Image_action($param = 0)
		{
			$dirname = __CORE_DIR__.'/image/slider/';
			$dir = opendir($dirname);
			$image = array();
			while($file = readdir($dir)) {
				if($file != '.' && $file != '..' && !is_dir($dirname.$file))
				{
					$image[] = $file;
				}
			}

			closedir($dir);
			$rand = array_rand($image,3);
			foreach ($rand as $key => $value) {
				$rand[$key] = $image[$value];
			}

			$this->objSmarty->assign("image",$rand);
			$template = __MOD_DIR__.'Slider_module/template/image.html';
			$html = $this->objSmarty->fetch($template);
			return $html;

		}
	}
?>