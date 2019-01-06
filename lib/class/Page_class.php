<?php
	abstract class Page
	{
		protected $objSmarty;

		public function __construct()
		{
			$this->objSmarty = new Smarty();

			$this->objSmarty->compile_check = true;
			$this->objSmarty->debugging = false;
			$this->objSmarty->force_compile = false;
			//$this->objSmarty->allow_php_tag = true;
			$this->objSmarty->php_handling = Smarty::PHP_ALLOW;
			$this->objSmarty->error_reporting = E_ALL & ~E_NOTICE;
			$this->objSmarty->assign("lang",Lang::GetArray());
		}


		public function getContent($sspage='index',$param='')
		{
			if($sspage=='')$sspage='index';
			$sspage = strtolower($sspage);
			$name = ucfirst(strtolower($this->name));
			$url = __PAGE_DIR__.$name."_page/template/$sspage.css";
			
			if(file_exists($url)){
				Site::getInstance()->addStyle("page/".$name."_page/template/$sspage.css");
			}

			$url = __PAGE_DIR__.$name."_page/template/$sspage.js";
			
			if(file_exists($url)){
				Site::getInstance()->addjavascript("page/".$name."_page/template/$sspage.js");
			}
			return $this->$sspage($param);
		}

		public function constructHead($title='',$description='',$keywords='',$canonical='')
		{
			$template = __MOD_DIR__.'Default_module/template/head.html';
			$this->objSmarty->assign('description',$description);
			$this->objSmarty->assign('keywords',$keywords);
			$this->objSmarty->assign('canonical',$canonical);
			$this->objSmarty->assign('title',$title);
			$this->objSmarty->assign('sheets',Site::getInstance()->getStyles());
			$html = $this->objSmarty->fetch($template);
			return $html;
		}
	}
?>