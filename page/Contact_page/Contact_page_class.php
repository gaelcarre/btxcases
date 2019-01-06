<?php
	class Contact_page extends Page
	{

		public function __construct()
		{
			parent::__construct();
			$this->name="Contact";
		}


		public function getHead($sspage='',$param='')
		{
			$title = "Contact - Music For Dancers";
			$description = 'Contactez moi soit par e-mail soit par courrier';
			$keywords = 'Gaël CARRE,contact';
			$canonical = 'http://www.gaelcarre.fr/contact.html';
			return parent::constructHead($title,$description,$keywords,$canonical);
		}

		public function Index($param = "")
		{
			$template = __PAGE_DIR__.'Contact_page/template/index.html';

			$resultat=false;
			$postsend = false;

			if(isset($_POST['content']) and $_POST['content'] != "")
			{
				$postsend= true;
				$headers = 'From : "'.$_POST['mail'].'"<'.$_POST['mail'].'>'."\n";
				$headers .='Content-Type: text/html; charset="iso-8859-1"'."\n";
				$headers .='Reply-To: '.$_POST['mail']."\n";
				$resultat = mail(Site::getInstance()->getEmail(),
				$resultat = mail("me@gaelcarre.fr",
					"Contact MusicForDancers",
					$_POST['content'],$headers);
			}


			$this->objSmarty->assign("resultat",$resultat);
			$this->objSmarty->assign("postsend",$postsend);
			$html = $this->objSmarty->fetch($template);
			return $html;
		}
	}
?>