<?php
	$file = simplexml_load_file("../conf/conf.xml");
	if(isset($_GET['type']))
		$_POST['type'] = $_GET['type'];
	$type = (isset($_POST['type']))?$_POST['type']:"";
	if($type == "dev")
	{
		$t = "dev";
		$type = "edit";
	}
		
	if($type == "prod")
	{
		$t = "prod";
		$type = "edit";
	}
		
	if($type == "edit")
	{
		foreach ($file as $conf) {
			$id = $conf['id'];
			$id = "{$id}";	
			if($id != "state")
			{
				if(isset($_POST['id']) and $_POST['id'] != "")
				changeConf($id,$_POST[$id],$conf);
			}			
			else{
				if($t != "")
					changeConf("state",$t,$conf);
			}
		}
	}
	if($type == "add")
	{
		$elt = $file->addChild("conf");
		$elt->addAttribute("id",$_POST['id']);
		$elt->addChild("value",$_POST['value']);
		$elt->addChild("com",$_POST['com']);
	}


	file_put_contents("../conf/conf.xml",$file->asXML());

	function changeConf($id,$value,$conf)
	{
		$conf->value = $value;		
	}
?>
	<form action="index.php?action=conf" method="POST">
		<input type="hidden" value="edit" name="type">
	<table class="table table-striped table-bordered table-hover">
		<caption><h2>Configurations</h2></caption>
		<tbody>
	<?php
		foreach ($file as $conf) {
			echo "<tr>";
			$id = $conf['id'];
			if($id == "state")
			{
				?>
				<td>Etat: <?php echo "{$conf->com}";?></td>
				<td><button class="btn btn-large <?php echo ("{$conf->value}" == "dev")?"btn-primary":""; ?>"><a href="index.php?action=conf&type=dev">Dev</a></button></td>
				<td><button class="btn btn-large <?php echo ("{$conf->value}" == "prod")?"btn-primary":""; ?>"><a href="index.php?action=conf&type=prod">Prod</a></button></td>
				<?php
			}
			else
			{
				echo "<td>$id</td>";
				echo "<td colspan='2'>";
					echo "<input type='text' name='$id' value='"."{$conf->value}"."'>";
				echo "</td>";
			}

			echo "</tr>";
		}
	?>
	</tbody>
	</table>
	<input type="submit" class="btn btn-large btn-success" value="Valider Changement">
	</form>

	<form action="index.php?action=conf" method="post" class="form-inline">
		<input type="hidden" name="type" value="add">
		<input type="text" class="input-small" name="id" placeholder="id" required>
		<input type="text" class="input-medium" name="value" placeholder="value" required>
		<input type="text" class="input-medium" name="com" placeholder="commentaire">
		<button type="submit" class="btn">Ajouter une configuration</button>

	</form>