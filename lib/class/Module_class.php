<?php
	abstract class Module
	{
		protected $objSmarty;
		protected $name;

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
		public function getContent($action='',$param='')
		{

			
			$act = $action;
			if($action != '')
			{
				$action = $action.'_action';
				$action = strtolower($action);
				$act = strtolower($act);
				$url = __MOD_DIR__.$this->name."_module/template/$act.css";

				if(file_exists($url))
				{
					Site::getInstance()->addStyle("module/".$this->name."_module/template/$act.css");
				}
				return $this->$action($param);
			}
			return $html;
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