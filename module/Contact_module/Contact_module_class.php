<?php
	class Contact_module extends Module
	{

		public function __construct()
		{
			parent::__construct();
			$this->name = "Contact";
		}

		

		public function getHead($page='',$param='')
		{
			return parent::constructHead('Accueil-localhost');
			
		}

		public function Default_action($param = "")
		{
			$template = __MOD_DIR__.'Contact_module/template/contact.html';
			$this->objSmarty->assign('title',LANG::get('contact_title'));
			$this->objSmarty->assign('your',LANG::get('contact_your'));
			$this->objSmarty->assign('send',LANG::get('contact_send'));

			$html = $this->objSmarty->fetch($template);
			return $html;
		}

		public function Send_action($param = "")
		{
			if(isset($_POST['content']) and $_POST['content'] != "")
			{
				$mailObj = new Mail();
				$mailObj->SetType('html')->Send("gaelcarre@gmail.com","From gaelcarre.fr",$_POST['content'],$_POST['mail'],$_POST['mail']);
			}
			redirect("http://www.gaelcarre.fr");
		}
	}
?>