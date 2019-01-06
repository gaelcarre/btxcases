<?php 
	include("function_conf.php");
	if(isset($_GET['action']) and file_exists("include/".$_GET['action'].".php"))
		$action = $_GET['action'];
	else
		$action = "conf"; 
?>
<!doctype html>
<head>
	<title>BACKEND</title>
	<?php if(getConf("state")=="prod"){ ?>
	<base href="<?php echo getConf("url");?>/backend" />
	<?php }?>

	<!-- STYLE -->
	<link rel="stylesheet" href="<?php echo getConf("url");?>/style/bootstrap.css">
	<link rel="stylesheet" href="<?php echo getConf("url");?>/style/bootstrap-responsive.css">
</head>
<body>
	<div class="container-fluid">
		<div class="row-fluid">
			<h1>Backend : <?php echo getConf("name");?></h1>
		</div>		
		<div class="row-fluid">
			<div class="span2" id="menu">
				<div class="well sidebar-nav" style="padding:0px;">
					<ul class="nav nav-list">
						<li class="<?php echo ($action == "conf")?"active":"";?>"><a href="index.php?action=conf">Configuration</a></li>
						<li class="<?php echo ($action == "route")?"active":"";?>"><a href="index.php?action=route">Route</a></li>
					</ul>
				</div>
			</div>
			<div class="span10 well">
				<?php 
					include("include/$action.php");
				?>
			</div>
		</div>
		
	</div>
</body>