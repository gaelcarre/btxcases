<?php
	class Admin_page extends Page
	{

		public function __construct()
		{
			parent::__construct();
			$this->name="Admin";
		}


		public function getHead($sspage='',$param='')
		{
			$title = "Admin";
			$description = "";
			$keywords = '';
			$canonical = '';
			return parent::constructHead($title,$description,$keywords,$canonical);
		}

		public function Index($param = "")
		{
			$user = Site::getInstance()->getUser();
			if(!$user->isValid())
			{
				$template = __PAGE_DIR__.'Admin_page/template/login.html';
				$this->objSmarty->assign("user_form",$user->Login_action());
				$html = $this->objSmarty->fetch($template);
				return $html;
			}
			else
			{
				$template = __PAGE_DIR__.'Admin_page/template/index.html';
				$count = Site::getInstance()->getDB()->SelectFirst("stats_visites", "", "SUM(pages_vues) as c");
				//print_r($count);print_r("<br>");
				$this->objSmarty->assign("visite_total",$count);

				$visites = Site::getInstance()->getDB()->Select("stats_visites", 
					"date_visite between current_date()-7 AND current_date()",
					"SUM(pages_vues)as c,date_visite",
					"GROUP BY date_visite");

				//print_r($visites);print_r("<br>");
				$this->objSmarty->assign("visite_week", $visites);
				$html = $this->objSmarty->fetch($template);
				return $html;
			}
		}

		public function Actus($param = "")
		{
			$user = Site::getInstance()->getUser();
			if(!$user->isValid())
			{
				redirect("/admin");
			}
			else
			{
				$template = __PAGE_DIR__.'Admin_page/template/actus.html';
				$actus = Site::getInstance()->getDB()->Select("News", "", "*", "ORDER BY news_date");
				$this->objSmarty->assign("actus", $actus);
				$html = $this->objSmarty->fetch($template);
				return $html;
			}
		}

		public function deleteNews($param = "")
		{
			$user = Site::getInstance()->getUser();
			if($user->isValid())
			{
				print_r(Site::getInstance()->getDB()->Delete("News", "news_id = ".$_POST['idToDelete']));
			}
		}

		public function editNews($param = "")
		{
			$user = Site::getInstance()->getUser();
			if($user->isValid())
			{
				print_r(Site::getInstance()->getDB()->UPDATE("News", "news_content = '".$_POST['content']."'",
					"news_id = ".$_POST['idToEdit']));
			}
		}

		public function addActu($param = "")
		{
			$user = Site::getInstance()->getUser();
			if($user->isValid())
			{
				$date = date('Y-m-d H:i:s');
				 Site::getInstance()->getDB()->InsertWithoutDate("News", 
				 	"news_date,news_title,news_content, news_lang",
				 	"'$date', '".$_POST['item_title']."', '".$_POST['item_content']."', '".$_POST['item_lang']."'");

				 redirect("/admin/actus");
			}	
		}

		public function Styles($param = "")
		{
			$user = Site::getInstance()->getUser();
			if(!$user->isValid())
			{
				redirect("/admin");
			}
			else
			{
				$template = __PAGE_DIR__.'Admin_page/template/styles.html';
				$categoriesDB = Site::getInstance()->getDB()->Select("Category");
				$categories = array();
				foreach ($categoriesDB as $key => $value) {
					$aCat = new Category(
						$value['Category_id'],
						$value['Category_title'],
						$value['Category_image'],
						$value['Category_url']);
					$descriptions = Site::getInstance()->getDB()->Select("CategoryDescription", "Cat_id = ".$aCat->getId());
					foreach ($descriptions as $key2 => $value2) {
						$aCat->addDescription(new CategoryDescription(
							$value2['CategoryDescription_id'],
							$value2['Cat_id'],
							$value2['Category_lang'],
							$value2['Category_description']));
					}
					array_push($categories, $aCat);
				}
				$this->objSmarty->assign("styles", $categories);
				$this->objSmarty->assign("langues", ['FR', 'EN', 'IT', 'ES']);
				$html = $this->objSmarty->fetch($template);
				return $html;
			}
		}

		public function addDesc($param = "")
		{
			$user = Site::getInstance()->getUser();
			if($user->isValid())
			{
				print_r(Site::getInstance()->getDB()->InsertWithoutDate("CategoryDescription", "Cat_id, Category_lang, Category_description", 
					$_POST['idToEdit'].",'".$_POST['lng']."','".$_POST['content']."'"));
			}
		}

		public function deleteDesc($param = "")
		{
			$user = Site::getInstance()->getUser();
			if($user->isValid())
			{
				print_r(Site::getInstance()->getDB()->Delete("CategoryDescription", "CategoryDescription_id = ".$_POST['idToDelete']));
			}
		}

		public function editDescription($param = "")
		{
			$user = Site::getInstance()->getUser();
			if($user->isValid())
			{
				print_r(Site::getInstance()->getDB()->UPDATE("CategoryDescription", "Category_description = '".$_POST['content']."'",
					"CategoryDescription_id = ".$_POST['idToEdit']));
			}
		}

		public function addStyle($param = "")
		{
			$user = Site::getInstance()->getUser();
			if($user->isValid())
			{
				$url = str_replace(' ', '-', $_POST['item_title']);
				$url = str_replace('\'', '-', $url);
				$url = str_replace('&', '-', $url);
				$image = null;
				$uploaddir = __IMG_DIR__;
				$uploadfile = $uploaddir . basename($_FILES['item_image']['name']);


				if(isset($_FILES['item_image']))
				{
					$uploaddir = __IMG_DIR__;
					$uploadfile = $uploaddir . basename($_FILES['item_image']['name']);
					$image = $_FILES['item_image']['name'];
					if(!file_exists($uploadfile))
						$reg = move_uploaded_file($_FILES['item_image']['tmp_name'], $uploadfile);
					else
						$reg = true;
				}

				$insert = ("Category_title, Category_url".(($image != null && $reg)?",Category_image":""));
				$values = ("'".$_POST['item_title']."', '$url'".(($image != null && $reg)?", '$image'":""));

				 Site::getInstance()->getDB()->InsertWithoutDate("Category", $insert,$values);

				 redirect("/admin/styles");
			}	
		}

		public function description($param = "")
		{
			$user = Site::getInstance()->getUser();
			if(!$user->isValid())
			{
				redirect("/admin");
			}
			else
			{
				$template = __PAGE_DIR__.'Admin_page/template/description.html';
				$descriptions = new Descriptions();
				$descDB = Site::getInstance()->getDB()->Select("Description");
				foreach ($descDB as $key => $value) {
					$descriptions->ajouterDescription(new Description(
						$value['Description_id'],
						$value['Description_content'],
						$value['Description_lang']));
				}
				$this->objSmarty->assign("descriptions", $descriptions);
				$html = $this->objSmarty->fetch($template);
				return $html;
			}
		}

		public function editIndexDesc($param = "")
		{
			$user = Site::getInstance()->getUser();
			if($user->isValid())
			{
				$content = str_replace("'","&#39;", $_POST['content']);
				print_r(Site::getInstance()->getDB()->UPDATE("Description", "Description_content = '$content'",
					"Description_id = ".$_POST['idToEdit']));
			}
		}

		public function cds($param = "")
		{
			$user = Site::getInstance()->getUser();
			if(!$user->isValid())
			{
				redirect("/admin");
			}
			else
			{
				$template = __PAGE_DIR__.'Admin_page/template/cds.html';
				$categoriesDB = Site::getInstance()->getDB()->Select("Category");
				$categories = array();
				foreach ($categoriesDB as $key => $value) {
					$aCat = new Category(
						$value['Category_id'],
						$value['Category_title'],
						$value['Category_image'],
						$value['Category_url']);
					array_push($categories, $aCat);
				}
				$this->objSmarty->assign("styles", $categories);
				$cdsDB = Site::getInstance()->getDB()->Select("CD");
				$cds = array();
				foreach ($cdsDB as $key => $value) {
					$aCD = new CD(
						$value['CD_id'], $value['CD_links'], $value['Category']);
					array_push($cds, $aCD);
				}
				$this->objSmarty->assign("cds", $cds);
				$html = $this->objSmarty->fetch($template);
				return $html;
			}
		}

		public function editerCD($param = "")
		{
			$user = Site::getInstance()->getUser();
			if(!$user->isValid())
			{
				redirect("/admin");
			}
			else
			{

				//edition du lien
				if(isset($_POST['link_cd']) and $_POST['link_cd'] != "")
				{
					Site::getInstance()->getDB()->Update("CD", "CD_links = '".$_POST['link_cd']."'", 
						"CD_id = ".$param);
				}


				$template = __PAGE_DIR__.'Admin_page/template/editerCD.html';
				$categoriesDB = Site::getInstance()->getDB()->Select("Category");
				$cdDB = Site::getInstance()->getDB()->SelectFirst("CD", "CD_id = $param");
				$aCD = new CD(
				$cdDB['CD_id'], $cdDB['CD_links'], $cdDB['Category']);
				$cdDescDB = Site::getInstance()->getDB()->Select("CDDescription", "CDD_cdid = ".$aCD->getId());
				foreach ($cdDescDB as $key => $value) {
					$aCDDesc = new CDDescription(
						$value['CDDescription_id'],
						$value['CDD_cdid'],
						$value['CD_lang'],
						$value['CD_description'],
						$value['CD_title']);
					$aCD->addDescription($aCDDesc);
				}
				$this->objSmarty->assign("cd", $aCD);
				$html = $this->objSmarty->fetch($template);
				return $html;
			}
		}

		public function editerDescriptionCD($param = "")
		{
			$user = Site::getInstance()->getUser();
			if(!$user->isValid())
			{
				redirect("/admin");
			}
			else
			{

				//edition de la description
				if(isset($_POST['inputTitle']) and $_POST['inputTitle'] != "")
				{
					Site::getInstance()->getDB()->Update("CDDescription", 
						"CD_title = '".$_POST['inputTitle']."', CD_Description = '".$_POST['inputContent']."'", 
						"CDDescription_id = ".$param);
				}

				$template = __PAGE_DIR__.'Admin_page/template/editerDescriptionCD.html';
				$descriptionDB = Site::getInstance()->getDB()->SelectFirst("CDDescription","CDDescription_id=$param");
				$aCDDesc = new CDDescription(
					$descriptionDB['CDDescription_id'],
					$descriptionDB['CDD_cdid'],
					$descriptionDB['CD_lang'],
					$descriptionDB['CD_description'],
					$descriptionDB['CD_title']);
				$this->objSmarty->assign("desc", $aCDDesc);
				$html = $this->objSmarty->fetch($template);
				return $html;
			}
		}

		public function ajouterDescriptionCD($param = "")
		{
			$user = Site::getInstance()->getUser();
			if(!$user->isValid())
			{
				redirect("/admin");
			}
			else
			{

				//ajout de la description
				if(isset($_POST['inputTitle']) and $_POST['inputTitle'] != "")
				{
					Site::getInstance()->getDB()->InsertWithoutDate(
						"CDDescription",
						"CDD_cdid,CD_title, CD_description, CD_lang",
						"$param,'".$_POST['inputTitle']."','".$_POST['inputContent']."','".$_GET['lng']."'");
					redirect("/admin/editerCD/$param");
				}

				$template = __PAGE_DIR__.'Admin_page/template/ajouterDescriptionCD.html';

				$this->objSmarty->assign("selectedLng", $_GET['lng']);
				$this->objSmarty->assign("cd_id", $param);
				$html = $this->objSmarty->fetch($template);
				return $html;
			}
		}

		public function ajouterCD($param = "")
		{
			$user = Site::getInstance()->getUser();
			if(!$user->isValid())
			{
				redirect("/admin");
			}
			else
			{

				//ajout du CD
				if(isset($_POST['link']) and $_POST['link'] != "")
				{
					Site::getInstance()->getDB()->InsertWithoutDate(
						"CD",
						"CD_links, Category",
						"'".$_POST['link']."',".$_POST['category']);
					redirect("/admin/cds");
				}

				$template = __PAGE_DIR__.'Admin_page/template/ajouterCD.html';

				$categoriesDB = Site::getInstance()->getDB()->Select("Category");
				$this->objSmarty->assign("styles", $categoriesDB);

				$html = $this->objSmarty->fetch($template);
				return $html;
			}
		}

	}
?>