<!DOCTYPE html>
<html lang="en">
<head>
<?php
	$sge_billing_path=(dirname(__FILE__));
	include("$sge_billing_path/../../DDBB_connection.php");
	include("$sge_billing_path/../../DDBB_query.php");
	include("$sge_billing_path/../../head.php");
?>
</head>
<body>
	<?php
		include("$sge_billing_path/../../header.php");
	?>
	<div class="container-fluid">
		<div class="row-fluid" id="top">
			<div class="span12">
			<center>
				<div class="well" id="wellt">
				<?php
				if ($type == 'group') { echo '<a id="goback" class="pull-left" href="sge_billing.php?period='.$period.'"><i class="icon-share-alt fliph"></i>Back</a>'; }
				?>
				<h4 id="title"><?php echo "$ttittle $tdate"; ?></h4>
				<small><i>* Walltime value represented in hours</i></small><br><br>
				<a class="btn btn-small pull-left" href="#" onclick="printa();"><i class="icon-print"></i></a>
					<div class="pull-right">
						<div id="monthly">
							<select name=period id="period" class='input-medium pull-right' onchange="<?php echo $onchange; ?>">
							<option value="">Change month...</option> 
							<?php
							# Get all dates with data
							$months = Account::all(array('select' => "DISTINCT date_format(date,'%Y-%m') as date", "order" => "date DESC"));
							foreach ($months as $m) {
								echo "<option value='".$m->date->format('Y-m')."'>".$m->date->format('F Y')."</option>";
							}
							?>
							</select> <br>
						</div>
					</div>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered taules" id="ctable">
					<thead>
					<tr>
						<th class=firstcol><?php echo $tfirstcol; ?></th>
						<th>Total Jobs Dedicated</th>
						<th>OK Jobs Dedicated</th>
						<th>OK % Dedicated</th>
						<th>Error Jobs Dedicated</th>
						<th>Error % Dedicated</th>
						<th>Wall Time Dedicated</th>
						<th class='css/shared_usage'>Total Jobs Shared</th>
						<th class='css/shared_usage'>OK Jobs Shared</th>
						<th class='css/shared_usage'>OK % Shared</th>
						<th class='css/shared_usage'>Error Jobs Shared</th>
						<th class='css/shared_usage'>Error % Shared</th>
						<th class='css/shared_usage'>Wall Time Shared</th>
					</tr>
					</thead>
					<tbody>
					<?php
						$tottotal = $totok = $totokp = $toterr = $toterrp = $totcpu = $totwall = $tottotal_dedicated = $totok_dedicated = $toterr_dedicated = $totwall_dedicated = $tottotal_shared = $totok_shared = $toterr_shared = $totwall_shared = $tottotal = $totok = $toterr =$totokp_dedicated=$toterrp_dedicated=$totokp_shared=$toterrp_shared=0;
						$parsed_group=array();	
						for ($index=0;$index < count($query); $index++) {
						$jobs_dedicated =$ok_dedicated =$okp_dedicated =$err_dedicated =$errp_dedicated =$wall_time_dedicated =$jobs_shared =$ok_shared =$okp_shared =$err_shared =$errp_shared= $wall_time_shared=0;
							if ($type == 'group') {
								$item = $query[$index]->owner;
								// If the group has not been parsed, we initialize the variable as not_found.
								if (!isset($parsed_group[$item])) {
									$parsed_group[$item]='not_found';
								}
								if ($parsed_group[$item] != 'found') {
									$firstcol = $item;
								}
							}else{
								$item = $query[$index]->groupname;
								// If the group has not been parsed, we initialize the variable as not_found.
								if (!isset($parsed_group[$item])) {
									$parsed_group[$item]='not_found';
								}
								if ($parsed_group[$item] != 'found') {
									$firstcol = "<a href='sge_billing.php?type=group&groupname=$item&period=$period'>$item</a>";
								}
							}
							$dedicated = $query[$index]->dedicated;
						
							if ($parsed_group[$item] != 'found') {
								if ($dedicated == 'dedicated'){
									$jobs_dedicated= $query[$index]->jobs;
									$ok_dedicated = $query[$index]->ok_jobs;
									$err_dedicated = $jobs_dedicated - $ok_dedicated;
									@$okp_dedicated = number_format($ok_dedicated / $jobs_dedicated * 100, 1, ".", "");
									@$errp_dedicated = number_format($err_dedicated / $jobs_dedicated * 100, 1, ".", "");
									$wall_time_dedicated = number_format($query[$index]->wallclock/3600, 2, ".", "");
								}else{
									$jobs_dedicated= 0;
									$ok_dedicated = 0;
									$err_dedicated = 0;
									@$okp_dedicated = 0;
									@$errp_dedicated = 0;
									$wall_time_dedicated = 0;
								}
								// Ok, now let's look for shared cluster usage for this group. We start parsing the aray from $index position:
								for ($new_index=$index;$new_index < count($query); $new_index++){
								// Look for the same groupn
									if ($type == 'group') {	
										$new_item=$query[$new_index]->owner;
									}else{
										$new_item=$query[$new_index]->groupname;
									}
										$new_dedicated = $query[$new_index]->dedicated;
										if (( "$new_item" == "$item" ) && ("$new_dedicated" == 'shared')) {
											$jobs_shared = $query[$new_index]->jobs;
											$ok_shared = $query[$new_index]->ok_jobs;
											$err_shared = $jobs_shared - $ok_shared;
											@$okp_shared = number_format($ok_shared / $jobs_shared * 100, 1, ".", "");
											@$errp_shared = number_format($err_shared / $jobs_shared * 100, 1, ".", "");
											$wall_time_shared = number_format($query[$new_index]->wallclock/3600, 2, ".", "");
											$parsed_group[$new_item]='found';
										}
								}
								echo "<tr>
								<td class=firstcol>$firstcol</td>
								<td>$jobs_dedicated</td>
								<td>$ok_dedicated</td>
								<td>$okp_dedicated</td>
								<td>$err_dedicated</td>
								<td>$errp_dedicated</td>
								<td>$wall_time_dedicated</td>
								<td class='shared_usage'>$jobs_shared</td>
								<td class='shared_usage'>$ok_shared</td>
								<td class='shared_usage'>$okp_shared</td>
								<td class='shared_usage'>$err_shared</td>
								<td class='shared_usage'>$errp_shared</td>
								<td class='shared_usage'>$wall_time_shared</td>
								</tr>
								";

								$tottotal_dedicated += $jobs_dedicated; 
								$totok_dedicated += $ok_dedicated ;
								$toterr_dedicated += $err_dedicated; 
								$totwall_dedicated += $wall_time_dedicated ;
								$tottotal_shared +=  $jobs_shared;
								$totok_shared +=  $ok_shared;
								$toterr_shared += $err_shared;
								$totwall_shared += $wall_time_shared;
								$tottotal += $jobs_dedicated + $jobs_shared;
								$totok += $ok_dedicated + $ok_shared;
								$toterr += $err_dedicated + $err_shared;
								$totwall += $wall_time_dedicated + $wall_time_shared;
							}
						}
						if ($tottotal_dedicated != '0'){
							$totokp_dedicated = number_format($totok_dedicated / $tottotal_dedicated * 100, 1, ".", "");
							$toterrp_dedicated = number_format($toterr_dedicated / $tottotal_dedicated * 100, 1, ".", "");
						}
						if ($tottotal_shared != '0'){
							$totokp_shared = number_format($totok_shared / $tottotal_shared * 100, 1, ".", "");
							$toterrp_shared = number_format($toterr_shared / $tottotal_shared * 100, 1, ".", "");
						}

						echo "</tbody>
						<tfoot>
						<th class=firstcol>TOTAL</th>
						<th>$tottotal_dedicated</th>
						<th>$totok_dedicated</th>
						<th>$totokp_dedicated</th>
						<th>$toterr_dedicated</th>
						<th>$toterrp_dedicated</th>
						<th>$totwall_dedicated</th>
						<th class='shared_usage'>$tottotal_shared</th>
						<th class='shared_usage'>$totok_shared</th>
						<th class='shared_usage'>$totokp_shared</th>
						<th class='shared_usage'>$toterr_shared</th>
						<th class='shared_usage'>$toterrp_shared</th>
						<th class='shared_usage'>$totwall_shared</th>
						</tfoot>
						";
					?>
					</table><br>
					<center>
					<small><i>** Click on a cell to plot its column values</i></small>
					</center>
				</div>				
				</center>
			</div><!--/span-->
		</div><!--/row-->

		<div class="row-fluid" id="plotrow" style="display:none">
			<div class="span12">
			<center>
				<div class="well" id="wellp">
				<center>
				<h4 id="plottitle"></h4>
					<div id="plot">							
					</div>
				</center>
				</div>
				</center>
			</div>
		</div>
	</div><!--/.fluid-container-->
	<hr>
	<?php
		$sge_billing_path=(dirname(__FILE__));
		include("$sge_billing_path/../../footer.php");
	?>
</body>
</html>
