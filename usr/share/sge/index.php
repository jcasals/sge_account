<!DOCTYPE html>
<html lang="en">
<head>
<?php
	$path=(dirname(__FILE__));
	include("$path/head.php");
	@$msg = $_GET['msg'];
	if ($msg == 'E') {
		$message = "<div class='alert alert-error alert-pic fade in'><button type='button' class='close' data-dismiss='alert'>&times;</button><b>Ooops!</b> It seems that an error occurred. Please, try again!</div>";
	}
	include("$path/header.php");
?>
</head>
<body>
<div class="container-fluid">
	<div class="row-fluid cos">
		<div class="span12">
			<div class="well links">
				<h4>SGE</h4>
					<a href="sge.php">SGE</a> Accounting
					<br><br>
				<?php
					foreach (glob("$path/addons/index_addon/*.php") as $filename){
						include_once $filename;
					}
				?>
			</div><!-- well -->
		</div><!--/span-->
	</div><!--/row-->
</div><!--/.fluid-container-->
<hr>
<?php
	include('./footer.php');
		foreach (glob("$path/addons/footer_addon/*.php") as $filename){
			include_once  $filename;
		}
?>
</body>
</html>
