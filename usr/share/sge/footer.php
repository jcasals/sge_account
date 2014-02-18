<footer>
	<center>
	<?php
	$path=(dirname(__FILE__));
	foreach (glob("$path/addons/footer_addons/*.php") as $filename){
		include_once $filename;
	}
	?>
	</center>
</footer>
