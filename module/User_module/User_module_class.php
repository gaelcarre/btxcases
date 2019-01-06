<?php
	class User_module extends Module
	{

		private $isConnect = false;

		public function __construct()
		{
			parent::__construct();
			$this->name = "User";
		}

		public static function isValid()
		{
			
			if(isset($_SESSION['user']) and $_SESSION['user'] == true)
			{
				return true;
			}
			else
				return false;
		}

		public function getHead($page='',$param='')
		{
			$template = __MOD_DIR__.'Default_module/template/head.html';
			$this->objSmarty->assign('description','accueil');
			$this->objSmarty->assign('keywords','');
			$this->objSmarty->assign('canonical','');
			$this->objSmarty->assign('title','Accueil-localhost');
			$html = $this->objSmarty->fetch($template);
			return $html;
		}


		public function Login_action($param = '')
		{
			
			if(isset($_POST['login_form_login']))
			{
				//print_r($_POST['login_form_login']."<br>");
				if($this->checkLog($_POST['login_form_login'], $_POST['login_form_pwd']))
				{
					print_r("pass ok<br>");
					$_SESSION['user']['user_login'] = true;
					redirect("/admin");
				}
					

			}
				
			$template = __MOD_DIR__.'User_module/template/login.html';
			$html = $this->objSmarty->fetch($template);
			return $html;
		}

		public function Register_action($param = '')
		{
			if(isset($_POST['mail']))
			{
				if(!Site::getInstance()->getDB()->Select_Exist('user',
					'user_mail = "'.$_POST['mail'].'"'))
				{
					if($_POST['password'] != $_POST['password2'])
						$this->objSmarty->assign('message_error','The two passwords are different');
					else
					{
						$code = getValidationCode();
						$data = '"'.$_POST['mail'].'","'.md5($_POST['password']).'",0,"'.$_POST['name'].'",
						"'.$_POST['surname'].'","'.$_POST['city'].'","'.$_POST['country'].'","'.$code.'"';
						$res = Site::getInstance()->getDB()->Insert(
							'user',
							'user_mail,user_password,user_valid,user_name,user_surname,user_city,user_country,user_validation_code',
							$data);
						if($res)
						{
							$this->objSmarty->assign('message','Register Success<br>You will receive an email to valid your account');
							$message = "Thanks for your registration.<br>To get access to the siteweb please confirm your email by clicking on the link <a href='#'>qsd</a>";
							$mailObj = new Mail();
							//$mailObj->SetType('html')->Send($_POST['mail'],"Confirm your registration",$message,"contact@my-shareproject.com","NO REPLY");
						}
						else
							$this->objSmarty->assign('message_error','Register Failed');
					}
				}
				else
					$this->objSmarty->assign('message_error','E-mail already exists');
				


			}

			$template = __MOD_DIR__.'User_module/template/register.html';
			$html = $this->objSmarty->fetch($template);
			return $html;
		}

		public function Logout_action($param = '')
		{
			$_SESSION['user'] = array();
			redirect("/");
		}

		public function Account_action($param = '')
		{
			if($param == ''){
				redirect("index.php?module=default&page=Error404");
			}
		}

		public function Valid_action($param = '')
		{
			if($param == '')
			{
				redirect("index.php?module=default&page=Error404");
			}
			else
			{
				if(empty($_SESSION['user']))
				{
					$param = explode('$',$param);
					$mail = $param[0];
					$code = $param[1];
					if(Site::getInstance()->getDB()->Select_Exist('user','user_mail="'.$mail.'" and user_validation_code="'.$code.'"'))
					{
						Site::getInstance()->getDB()->Update('user',"user_valid=1","user_mail = '$mail'");
						//redirect("index.php");
					}
					else
					{
						$this->objSmarty->assign("message","Sorry, this is a wrong validation code.<br>Try again");
						$template = __MOD_DIR__.'User_module/template/valid.html';
						$this->objSmarty->fetch($template);
					}

				}
				
			}
		}

		public function Forgot_action($param = '')
		{
			if(empty($_SESSION['user']))
			{
				
			}
		}

		public function getToken()
		{
			if($this->isValid())
				return $_SESSION['user']['user_token'];
		}

		public function getLogin()
		{
			if($this->isValid())
				return $_SESSION['user']['user_login'];
		}

		private function checkLog($login, $password)
		{
			//print_r("login:".(Site::getInstance()->getLogin())."<br>");
			//print_r("pass:".(Site::getInstance()->getPass())."<br>");
			return ($login == Site::getInstance()->getLogin() && $password == Site::getInstance()->getPass());
		}
	}
?>