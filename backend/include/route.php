<?php
	$file = simplexml_load_file("../conf/routes.xml");
	if(isset($_GET['type']))
		$_POST['type'] = $_GET['type'];
	$type = (isset($_POST['type']))?$_POST['type']:"";
	if($type == "edit")
	{
		foreach ($file as $route) {
			$name = $route['name'];
			changeRoute($name,$route);
		}
	}
	if($type == "add")
	{
		$elt = $file->addChild("route");
		$elt->addAttribute("name",$_POST['name']);
		$elt->addChild("url",$_POST['url']);
		$elt->addChild("page",$_POST['page']);
		$elt->addChild("action",$_POST['action']);
	}


	file_put_contents("../conf/routes.xml",$file->asXML());

	function changeRoute($name,$route)
	{

				$route->url = $_POST[$name.'-url'];
				$route->page = $_POST[$name.'-page'];
				$route->action = $_POST[$name.'-action'];	
	}

?>

	<form action="backend/index.php?action=route" method="POST">
		<input type="hidden" value="edit" name="type">
	<table class="table table-striped table-bordered table-hover">
		<caption><h2>Routes</h2></caption>
		<tbody>
	<?php
		foreach ($file as $route) {
			echo "<tr>";
			$name = $route['name'];
			
				echo "<td>$name</td>";
				echo "<td>";
					echo "<input type='text' name='$name-url' value='"."{$route->url}"."'>";
				echo "</td>";
				echo "<td>";
					echo "<input type='text' name='$name-page' value='"."{$route->page}"."'>";
				echo "</td>";
				echo "<td>";
					echo "<input type='text' name='$name-action' value='"."{$route->action}"."'>";
				echo "</td>";

			echo "</tr>";
		}
	?>
	</tbody>
	</table>
	<input type="submit" class="btn btn-large btn-success" value="Valider Changement">
	</form>

	<form action="backend/index.php?action=route" method="post" class="form-inline">
		<input type="hidden" name="type" value="add">
		<input type="text" class="input-small" name="name" placeholder="name" required>
		<input type="text" class="input-medium" name="url" placeholder="url" required>
		<input type="text" class="input-medium" name="page" placeholder="page" required>
		<input type="text" class="input-medium" name="action" placeholder="action">
		<button type="submit" class="btn">Ajouter une route</button>

	</form>