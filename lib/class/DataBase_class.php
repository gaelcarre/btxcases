<?php
class DataBase
{

	/*private $host = 'localhost';
	private $dbname = 'gaelcarre';
	private $login = 'root';
	private $pass = '';
	private $db;*/


	//default
	private $host = 'localhost';
	private $dbname = 'gaelcarre';
	private $login = 'root';
	private $pass = 'root';


	private $db;
	public function __construct($conf)
	{
		if($conf != null)
		{
			$dbs = simplexml_load_file(__CONF_DIR__."database.xml");
			foreach($dbs as $aDb)
			{

				if($aDb['id'] == $conf)
				{
					try{
						$this->db = new PDO('mysql:host='.$aDb->host.
							';dbname='.$aDb->dbname,
							$aDb->login,
							$aDb->pass,
							array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

					}
					catch(Exception $e)
					{
						echo 'Erreur : '.$e->getMessage().'<br />';
       					echo 'N° : '.$e->getCode();
					}
					
				}
				
			}
		}


		if($this->db == null)
		{
			print_r("Default Database");
			$this->db = new PDO('mysql:host='.$this->host.
			';dbname='.$this->dbname,
			$this->login,
			$this->pass,
			array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
		}
	}
	public function Select($table,$where = "",$select = "*",$options = '')
	{
		if($select == '') $select = '*';
		$sql = "SELECT $select FROM $table ";
		if($where != '')
			$sql.=' WHERE '.$where.' ';
		$sql .= $options;
		//print_r($sql."<br>");
		$res = $this->db->query($sql);
		if($res != null)
			return $res->fetchAll();
		else
			return null;
	}
	public function SelectFirst($table,$where = "",$select = "*",$options = '')
	{
		if($select == '') $select = '*';
		$sql = "SELECT $select FROM $table ";
		if($where != '')
			$sql.=' WHERE '.$where.' ';
		$sql .= $options;
		//print_r($sql);
		$res = $this->db->query($sql);
		if($res != null)
		{
			$res =  $res->fetchAll();
			if(empty($res))
				return null;
			else
				return $res[0];
		}
			
		else
			return null;
	}

	public function Select_Exist($table,$where,$select = "*")
	{
		$sql = "SELECT $select FROM $table WHERE $where";
		$res = $this->db->query($sql);
		return ($res->fetchColumn() > 0);

	}

	public function Insert($table,$value,$insert)
	{
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO ".$table."(".$table."_created_at,".$table."_update_at,$value)  VALUES($date,$date,$insert) ";
		$res = $this->db->query($sql);
		if($res == false)
			return false;
		else
			return true;
	}

	public function InsertWithoutDate($table,$value,$insert)
	{
		$sql = "INSERT INTO ".$table."($value)  VALUES($insert) ";
		//print_r($sql);
		$res = $this->db->query($sql);
		if($res == false)
			return false;
		else
			return true;
	}

	public function Delete($table, $where = "")
	{
		$sql = "DELETE FROM $table";
		if($where != "")
			$sql .= " WHERE $where";
		//print_r($sql);
		$res = $this->db->query($sql);
		if($res == false)
			return false;
		else
			return true;
	}

	public function Update($table, $set, $where ="")
	{
		$sql = "UPDATE $table SET $set";
		if($where != "")
			$sql .= " WHERE $where";
		//print_r($sql);
		$res = $this->db->query($sql);
		if($res == false)
			return false;
		else
			return true;

	}

	public function Execute($sql)
	{
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES,1);
		// $sql = "start transaction;".$sql;
		try {
			$st = $this->db->query($sql);
			return $st;
		}
		catch(PDOException $e)
		{
			print_r( "Database Problem: ".$e->getMessage());
			die();
		}
		
	}
}

?>