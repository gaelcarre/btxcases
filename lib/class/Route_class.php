<?php
	class Route
	{

		private $url;
		private $state;
		private $routes;
		private $default;

		public function __construct($state = "dev",$url = "index",$default= "Index")
		{
			$this->url = $url;
			$this->state = $state;

			$this->routes = simplexml_load_file(__CONF_DIR__."routes.xml");
		}

		public function getState()
		{
			return $this->state;
		}

		public function getPage()
		{
			if($this->url == "")
				$this->url == "index";

			$method = $this->state;

			return $this->$method();
		}

		private function dev()
		{
			$route = array();
			$route['page'] = (isset($_GET['page'])?$_GET['page']:$this->default);
			$route['action'] = (isset($_GET['action'])?$_GET['action']:null);
			$route['module'] = (isset($_GET['module'])?$_GET['module']:null);
			$route['param'] = (isset($_GET['param'])?$_GET['param']:null );

			return $route;
		}

		private function prod()
		{
			$route = array();
			$route['page'] = $this->default;
			$route['param'] = isset($_GET['param'])?$_GET['param']:null;

			$find = false;
			foreach ($this->routes as $key => $value) {
				//($this->url);print_r("<br>");
				//print_r("/".$value->url."/");print_r("<br>");
				if($value->url != "" and preg_match("/".$value->url."/", $this->url))
				{
					//print_r("find");print_r("<br>");
					$route['page'] = $value->page;
					$route['action'] = $value->action;
					$find = true;
				}
			}

			if(!$find){
				//check for class
				$temp_url = explode("/",$this->url);
				//print_r($temp_url);print_r("<br>");
				if(isset($temp_url[0]) and class_exists(ucfirst($temp_url[0])."_page")){
					// print_r($temp_url[0]." page exist<br>");
					$route['page'] = ucfirst($temp_url[0]);
					if(isset($temp_url[1])){
						//print_r($temp_url[1]." 1 value exist<br>");
						$obj = ucfirst($temp_url[0]."_page");
						$obj = new $obj();
						if(method_exists($obj, ucfirst($temp_url[1]))){
							//print_r($temp_url[1]." method exist<br>");
							$route['action'] = ucfirst($temp_url[1]);
							if(isset($temp_url[2])){
								//print_r($temp_url[2]." 2 value exist<br>");
								$route['param'] = $temp_url[2];
							}
						}

					}
					else
						$route["action"] = "index";
				} else if(isset($temp_url[0]) and class_exists(ucfirst($temp_url[0])."_module")) {
					// print_r("else if module".$temp_url);
					//print_r($temp_url[0]." module exist<br>");
					$route['module'] = ucfirst($temp_url[0]);
					if(isset($temp_url[1])){
						//print_r($temp_url[1]." action exist<br>");
						$obj = ucfirst($temp_url[0]."_module");
						$obj = new $obj();
						if(method_exists($obj, ucfirst($temp_url[1]).'_action')) {
							//print_r($temp_url[1]." method exist<br>");
							$route['action'] = ucfirst($temp_url[1]);
							if(isset($temp_url[2])) {
								//print_r($temp_url[2]." 2 value exist<br>");
								$route['param'] = $temp_url[2];
							}
						}
					}
					else
					$route["action"] = "index";
				}
			}

			return $route;
		}
		//p = page _ s = sspage _ r = param _ m = module _ a = action
		// public $route = array(
		// 	"page"=>"accueil",
		// 	"sspage"=>"",
		// 	"param"=>"",
		// 	"module"=>"",
		// 	"action"=>""
		// 	);
		// public $routes = array(
		// 	"/^(lang\/){1}[a-z]+([.]html)$/i"=>array("module"=>"lang","action"=>"change","param"=>2),
		// 	"/^([a-z]+[^\/])([.]html)$/i"=>"p",
		// 	"/^([a-z]+[\/]{1}[a-z]+)([.]html)$/i"=>"pa",
		// 	"/^([a-z]+[\/]{1}[a-z]+[\/]{1}[a-z0-9]+)([.]html)$/i"=>"psr"
		// );

		// public function getPage($param = "")
		// {
		// 	if($param == "")
		// 	{
		// 		return $this->route;
		// 	}
		// 	foreach ($this->routes as $key => $value) {
		// 		if(preg_match($key, $param))
		// 			if(is_array($value))
		// 				return $this->updateParam($value,substr($param,0,-5));
		// 			else
		// 				return $this->construct($value,substr($param,0,-5));
		// 	}
		// 	return $this->route;
		// }

		// public function updateParam($value,$param)
		// {
		// 	$param = explode("/",$param);

		// 	$value['param'] = $param[$value['param']-1];

		// 	return $value;
		// }
		// public function construct($value,$param)
		// {
		// 	$action = array();
		// 	$module = $value[0];
		// 	$value = str_split($value);
		// 	$param = explode("/",$param);

		// 	$i = 0;
		// 	foreach ($value as $v) {
		// 		if($v == "m")
		// 			$this->route['module'] =$param[$i];
		// 		if($v == "p")
		// 			$this->route['page'] =$param[$i];
		// 		if($v == "r")
		// 			$this->route['param'] =$param[$i];
		// 		if($v == "s")
		// 			$this->route['sspage'] =$param[$i];
		// 		if($v == "a")
		// 			$this->route['action'] =$param[$i];


		// 		$i++;
		// 	}

		// 	return $this->route;
		// }
	}
?>
