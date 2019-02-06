<?php

	class Site
	{

		private static $instance;

		private $state;
		private $conf;
		private $objDB;
		private $objPage = null;
		private $objModule = null;
		private $objUser = null;

		private $GETpage = 'Accueil';
		private $GETsspage = '';
		private $GETaction = '';
		private $GETmodule = '';
		private $GETparam = '';
		public $currentURL = '';

		private $objSmarty;

		private $ajax = 0;

		private $styles = array();
		private $javascript = array();



		private function __construct()
		{

			$this->objUser = new User_module();
			$this->conf = new Conf();

			$this->state = $this->conf->getConf("state");

			if(isset($_GET['url']))
				$this->currentURL = $_GET['url'];


			$this->currentURL = explode(".html",$this->currentURL);
			$this->currentURL = $this->currentURL[0];

			$this->objDB = new DataBase($this->state);
			$this->objSmarty = new Smarty();
			$this->objSmarty->force_compile = false;
			$this->objSmarty->assign("lang",Lang::GetArray());

			$objRoute = new Route($this->state,$this->currentURL,$this->conf->getConf("default_index"));
			$route = $objRoute->getPage();
			print_r($route);print_r("<br>");
			if(isset($route['module']))
				$this->GETmodule = ucfirst($route['module']);
			if(isset($route['page']))
				$this->GETpage = ucfirst($route['page']);
			if(isset($route['param']))
				$this->GETparam = ucfirst($route['param']);
			if(isset($route['action']))
				$this->GETaction = ucfirst($route['action']);

			/*$this->prepareLocalhost();*/
			if($this->GETmodule != "")
				$this->prepareModule();
			else
				$this->preparePage();



		}
		public function prepareLocalhost()
		{
			if(isset($_GET['module']))
				$this->GETmodule = ucfirst($_GET['module']);
			if(isset($_GET['page']))
				$this->GETpage = ucfirst($_GET['page']);
			if(isset($_GET['param']))
				$this->GETparam = ucfirst($_GET['param']);
			if(isset($_GET['action']))
				$this->GETaction = ucfirst($_GET['action']);
			if(isset($_GET['sspage']))
				$this->GETsspage = ucfirst($_GET['sspage']);
		}

		public function prepareModule()
		{
			$class = $this->GETmodule;
			$class_path = __MOD_DIR__.$class .'_module/'.$class.'_module_class.php';
			if(file_exists($class_path))
			{
				$module = $this->GETmodule.'_module';
				$this->objModule = new $module();
				$exist = method_exists($this->objModule,$this->GETaction.'_action');
				if(!$exist)
					$this->objPage = new Error404_page();
			}
			else
			{
				$this->objPage = new Error404_page();
			}
		}

		public function preparePage()
		{
			$class= $this->GETpage;
			$class_path = __PAGE_DIR__.$class.'_page/'.$class.'_page_class.php';
			if(file_exists($class_path))
			{
				$page = $class.'_page';
				//print_r($page);
				$this->objPage = new $page();
				if($this->GETaction != '')
				{

					if(!method_exists($this->objPage, ucfirst($this->GETaction)))
					{
						$this->objPage = new ErrorNotFound_page();
						$this->GETaction = 'Index';$this->ajax = 1;
					}
				}
			}
			else
			{
				$this->objPage = new ErrorNotFound_page();
				$this->GETaction = 'Index';$this->ajax = 1;
			}
		}
		public function requestAjax(){
			$this->ajax = 1;
		}
		public static function getInstance()
		{
			if(!isset(self::$instance))
				self::$instance = new Site();

			return self::$instance;

		}

		public function getDB()
		{
			return $this->objDB;
		}

		public function loadContent()
		{
			if($this->ajax == 0)
			{
				$this->objSmarty->assign("header",$this->loadHeader());

				if($this->GETmodule != "")
				{
					$content = $this->objModule->getContent($this->GETaction,$this->GETparam);
				}
				else
				{
					$content = $this->objPage->getContent($this->GETaction,$this->GETparam);
				}
				$this->objSmarty->assign("content",$content);

				$this->objSmarty->assign("footer",$this->loadFooter());

				$this->objSmarty->assign("javascript", $this->loadJavascript());

				$this->objSmarty->assign("head",$this->loadHead());
				$html = $this->objSmarty->fetch("super.html");
			}
			else
			{

				$html = $this->objPage->getContent($this->GETaction,$this->GETparam);
			}


					print_r($html);
		}

		public function loadJavascript()
		{
			$template = __MOD_DIR__.'Default_module/template/javascript.html';
			$this->objSmarty->assign("js_sheets", $this->getJavascript());
			$html = $this->objSmarty->fetch($template);
			return $html;
		}

		public function loadHead()
		{
				if($this->GETmodule != "")
					return ($this->objModule->getHead($this->GETaction, $this->GETparam));
				else
					return ($this->objPage->getHead($this->GETaction,$this->GETparam));
		}
		public function loadHeader()
		{
			//print_r($this->objPage);
			$template = __MOD_DIR__.'Default_module/template/header.html';
			$this->objSmarty->assign("menu",$this->loadMenu());
			//print_r($this->objPage->name);
			//print_r($this->objPage->name == 'Accueil');
			//$this->objSmarty->assign("isIndex", ($this->objPage->name == 'Accueil'));
			$this->objSmarty->assign("description",
				$this->getDB()->SelectFirst('Description',"Description_lang='".$_SESSION['lang']."'"));
			$html = $this->objSmarty->fetch($template);
			return $html;
		}
		public function loadMenu()
		{
			$template = __MOD_DIR__.'Default_module/template/menu.html';
			$lang = new Lang_module();
			$this->objSmarty->assign("lang_block",$lang->getContent("Default"));
			$this->objSmarty->assign("categories",$this->getDB()->Select('Category'));
			$html = $this->objSmarty->fetch($template);
			return $html;
		}
		public function loadFooter()
		{
			$template = __MOD_DIR__.'Default_module/template/footer.html';
			$html = $this->objSmarty->fetch($template);
			return $html;
		}

		public function addStyle($url)
		{
			$this->styles[] = $url;
		}
		public function getStyles()
		{
			return $this->styles;
		}
		public function addJavascript($url)
		{
			$this->javascript[] = $url;
		}
		public function getJavascript()
		{
			return $this->javascript;
		}



		public function getUser()
		{
			return $this->objUser;
		}

		public function getCurrentURL()
		{
			return $this->currentURL;
		}

		public function getLogin()
		{
			return $this->conf->getConf("login");
		}

		public function getPass()
		{
			return $this->conf->getConf("pwd");
		}

		public function getEmail()
		{
			return $this->conf->getConf("email");
		}
	}
?>
