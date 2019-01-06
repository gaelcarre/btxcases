<?php

	function redirect($page = "")
	{
		if($page == "")
			$page = Site::getInstance()->currentURL;
		header("Location:$page");
	}
	function getTime($time){
		$time = explode(':',$time);
		$hour = $time[0];
							$min = $time[1];
							if($hour >= 24){
								$hour -=24;
							}
							if(strlen($hour) <= 1)
								$hour = '0'.$hour;
							if(strlen($min) <= 1)
								$min = '0'.$min;
							$hour .='h'.$min;

		return $hour;
	}

	function getValidationCode($car = 20){
		$string = "";
		$chaine = "abcdefghijklmnpqrstuvwxy";
		srand((double)microtime()*1000000);
		for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;
	}

	function CryptData($string="") 
		{
		if($string=="") $string = $this->data;
		$string = serialize($string);
		srand((double) microtime() * 1000000); //for sake of MCRYPT_RAND
		$key = md5("squad2012"); //to improve variance
		/* Open module, and create IV */
		$td = mcrypt_module_open('des', '','cfb', '');
		$key = substr($key, 0, mcrypt_enc_get_key_size($td));
		$iv_size = mcrypt_enc_get_iv_size($td);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		/* Initialize encryption handle */
		
		if(mcrypt_generic_init($td, $key, $iv) != -1) 
			{
			/* Encrypt data */
			$c_t = mcrypt_generic($td, $string);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			$c_t = $iv.$c_t;
			return base64_encode($c_t);
			}
		}

	function DeCrypt($datacrypt) 
		{
		$datacrypt = base64_decode($datacrypt);
		$key = md5($this->clecrypt); //to improve variance
		/* Open module, and create IV */
		$td = mcrypt_module_open('des', '','cfb', '');
		$key = substr($key, 0, mcrypt_enc_get_key_size($td));
		$iv_size = mcrypt_enc_get_iv_size($td);
		$iv = substr($datacrypt,0,$iv_size);
		$string = substr($datacrypt,$iv_size);
		/* Initialize encryption handle */
		if(mcrypt_generic_init($td, $key, $iv) != -1) 
			{
			$c_t = mdecrypt_generic($td, $string);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			return unserialize($c_t);
			} //end if
		else return false;
		}


		function traiterVisite($site)
		{
			$ip   = $_SERVER['REMOTE_ADDR'];
   			$date = date('Y-m-d');

   			$sql = "INSERT INTO stats_visites (ip , date_visite , pages_vues) VALUES ('$ip' , '$date' , 1)
        		ON DUPLICATE KEY UPDATE pages_vues = pages_vues + 1	";

   			$site->getDB()->Execute($sql);
		}
?>