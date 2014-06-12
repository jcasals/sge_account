<!DOCTYPE html>
<html lang="en">
<head>
<?php
	$sge_eff_path=(dirname(__FILE__));
	include("$sge_eff_path/../../DDBB_connection.php");
	include("$sge_eff_path/../../DDBB_query.php");
	include("$sge_eff_path/../../head.php");
	// echo 	"<script type=\"text/javascript\" src=\"js/arnau.js\"></script>";
	// http://bicimap.es/wordpress/?p=1459
	//	echo	"<script type=\"text/javascript\" src=\"js/mysqlwslib.js\"></script>";
?>
</head>
<body>
	<?php
		include("$sge_eff_path/../../header.php");
	?>
	<div class="container-fluid">
		<div class="row-fluid" id="top">
			<div class="span12">
			<center>
				<div class="well" id="wellt">
				<?php
				if ($type == 'group') { echo '<a id="goback" class="pull-left" href="sge_eff.php?period='.$period.'"><i class="icon-share-alt fliph"></i>Back</a>'; }
				?>
				<h4 id="title"><?php echo "$ttittle $tdate"; ?></h4>
				<small><i>The efficiency is calculed dividing used_resources/requested_resources</i></small><br><br>
				<a class="btn btn-small pull-left" href="#" onclick="printa();"><i class="icon-print"></i></a>
					<div class="pull-right">
						<div id="monthly">
							<select name=period id="period" class='input-medium pull-right' onchange="<?php echo $onchange; ?>"> 
							<option value="">Change month...</option> 
							<?php
							# Get all dates with data
							$months = Efficiency::all(array('select' => "DISTINCT date_format(date,'%Y-%m') as date", "order" => "date DESC"));
							foreach ($months as $m) {
								echo "<option value='".$m->date->format('Y-m')."'>".$m->date->format('F Y')."</option>";
							}
							?>
							</select> <br>
						</div>
					</div>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered taules" id="efftable">
					<thead>
					<tr>
						<th class=firstcol><?php echo $tfirstcol; ?></th>
						<th>Number of jobs</th>
						<th>Average memory usage efficiency</th>
						<th>Average memory requested</th>
						<th>Average time usage efficiency</th>
						<th>Average time requested</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$all_groups;	
					for ($index=0;$index < count($eff_query); $index++) {
						$firstcol=$owner=$groupname=$mem_eff=$time_resource_eff=$number_jobs=$requested_mem=0;
						if ($type == 'group') {
							$groupname=$eff_query[$index]->owner;
							$firstcol = $groupname;
						}else{
							$groupname=$eff_query[$index]->groupname;
							$firstcol = "<a href='sge_eff.php?type=group&groupname=$groupname&period=$period'>$groupname</a>";
						}
						$all_groups[$index]=$groupname;
						$mem_eff = $eff_query[$index]->mem_avg;
						$time_eff = $eff_query[$index]->time_avg;
						$number_jobs=$eff_query[$index]->total_jobs;
						$requested_mem=number_format($eff_query[$index]->requested_mem/1024, 2,".","");
						$requested_time=number_format($eff_query[$index]->requested_time/3600, 2,".","");
					
						echo "<tr>
						<td class=firstcol>$firstcol</td>
						<td>$number_jobs</td>
						<td>$mem_eff %</td>
						<td>$requested_mem GB</td>
						<td>$time_eff %</td>
						<td>$requested_time hr</td>
						</tr>";
					}
					?>
					</tbody>
					</table><br>
				</div>				
				<!-- <div class="row-fluid" id="plotrow" style="display:none">
					<div class="span12">
						<center>
							<div class="well" id="wellp">
						<center>
						<h4 id="plottitle"></h4>
						<div id="plot"> </div>
						</center>
					</div>
					</div>
				</div> --!>

				</center>
			</div>
		</div>
	<hr>
	<?php
		$sge_eff_path=(dirname(__FILE__));
		include("$sge_eff_path/../../footer.php");
	?>
</body>
</html>
