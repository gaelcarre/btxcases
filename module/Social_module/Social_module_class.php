<?php
	class Social_module extends Module
	{

		public function __construct()
		{
			parent::__construct();
			$this->name = "Social";
		}

		

		public function getHead($page='',$param='')
		{
			return parent::constructHead('Accueil-localhost');
			
		}

		public function Default_action($param = '')
		{
		}

		public function Timelinetwitter_action($param = 0)
		{
			$user = "gaelcarre"; /* Nom d'utilisateur sur Twitter */
			$count = $param; /* Nombre de message à afficher */
			$date_format = 'd M Y, H:i:s'; /* Format de la date à afficher */
			$url = 'http://twitter.com/statuses/user_timeline/'.$user.'.xml?count='.$count;
			$oXML = simplexml_load_file( $url );
			$array = array();
			foreach( $oXML->status as $oStatus )
			{
			 $datetime = date_create($oStatus->created_at);
			 $date = date_format($datetime, $date_format)."\n";
			 //echo parse(utf8_decode($oStatus->text));
			 $array[] = $this->parseTwitter($oStatus->text);
			 
			}


			$this->objSmarty->assign("tweets",$array);
			$template = __MOD_DIR__.'Social_module/template/timelinetwitter.html';
			$html = $this->objSmarty->fetch($template);
			return $html;

		}

		public function Timelinefacebook_action($param = 0)
		{
			$template = __MOD_DIR__.'Social_module/template/timelinefacebook.html';
			$html = $this->objSmarty->fetch($template);
			return $html;
			

		}

		private function parseTwitter($text)
		{
			$text = preg_replace('#http://[a-z0-9._/-]+#i', '<a href="$0">$0</a>', $text);
			$text = preg_replace('#@([a-z0-9_]+)#i', '@<a href="http://twitter.com/$1">$1</a>', $text);
			$text = preg_replace('# \#([a-z0-9_-]+)#i', ' #<a href="http://search.twitter.com/search?q=%23$1">$1</a>', $text);
			return $text;
		}
	}
?>