<div class="navbar navbar-fixed-top navbar-inverse">
	<div class="navbar-inner">
		<a class="brand" href="index.php">Hub</a>
		<ul class="nav">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown">Accounting <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li><a href="sge.php">SGE</a></li>
				</ul>
			</li>                   
			<?php
				$path=(dirname(__FILE__));
				foreach (glob("$path/addons/head_addon/*.php") as $filename){
					include_once $filename;
				}
			?>
		</ul>
	</div>
</div>
