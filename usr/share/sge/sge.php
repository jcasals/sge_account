<!DOCTYPE html>
<html lang="en">
<head>
<?php
	$sge_path=(dirname(__FILE__));
	include("$sge_path/sge_db.php");
	include("$sge_path/sge_set.php");
	include("$sge_path/head.php");
?>
</head>
<body>
	<?php
	include("$sge_path/header.php");
	?>
	<div class="container-fluid">
		<div class="row-fluid" id="top">
			<div class="span12">
				<center>
				<div class="well" id="wellt">
				<?php
					if ($type == 'g') { echo '<a id="goback" class="pull-left" href="sge.php?period='.$period.'"><i class="icon-share-alt fliph"></i>Back</a>'; }
				?>
					<h4 id="title"><?php echo "$tfirst $tdate"; ?></h4>
					<br>
					<a class="btn btn-small pull-left" href="#" onclick="printa();"><i class="icon-print"></i></a>
					<div class="pull-right">
						<div id="monthly">
							<select name=period id="period" class='input-medium pull-right' onchange="<?php echo $onchange; ?>">
							<!--<option value="">Change month...</option> -->
							<?php
								# Get all dates with data
								$months = Account::all(array('select' => "DISTINCT date_format(date,'%Y-%m') as date", "order" => "date DESC"));
								foreach ($months as $m) {
									echo "<option value='".$m->date->format('Y-m')."'>".$m->date->format('F Y')."</option>";
								}
							?>
							</select>
							<br>
							<a class="pull-right switch" href="#"><small>Advanced search</small></a>
						</div>
						<div id="advanced" style="display:none">
							<form name="form" id="form" method="post" action="#" enctype="multipart/form-data" class="form-inline">
							From <input name="from" id="from" type="text" class="input-small required" readonly value="<?php echo $from; ?>"> 
							To <input name="to" id="to" type="text" class="input-small required" readonly value="<?php echo $to; ?>"> 
							<button type=submit class="btn" name="advanced">Go!</button>
							</form>
							<a class="pull-right switch" href="#"><small>Monthly search</small></a>
						</div>
					</div>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered taules" id="ctable">
					<thead>
						<tr>
						<th class=firstcol><?php echo $firstcolt; ?></th>
						<th>Total Jobs</th>
						<th>OK Jobs</th>
						<th>OK %</th>
						<th>Error Jobs</th>
						<th>Error %</th>
						<th>Wall Time</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$tottotal = $totok = $totokp = $toterr = $toterrp = $totcpu = $totwall = 0;
					foreach ($query as $q) {
						if ($type == "g") {
							$item = $q->owner;
							$firstcol = $item;
						} else {
							$item = $q->groupname;
							$firstcol = "<a href='sge.php?type=g&groupname=$item&period=$period'>$item</a>";
						}

						$jobs = $q->jobs;
						$ok = $q->ok_jobs;
						$err = $jobs - $ok;
						@$okp = number_format($ok / $jobs * 100, 1, ".", "");
						@$errp = number_format($err / $jobs * 100, 1, ".", "");
						$wall_time = number_format($q->cpu_time/3600, 2, ".", "");
						echo "<tr>
						<td class=firstcol>$firstcol</td>
						<td>$jobs</td>
						<td>$ok</td>
						<td>$okp</td>
						<td>$err</td>
						<td>$errp</td>
						<td>$wall_time</td>
						</tr>";
											
						$tottotal += $jobs;
						$totok += $ok;
						$toterr += $err;
						$totwall += $q->cpu_time;
					}

					$totokp = number_format($totok / $tottotal * 100, 1, ".", "");
					$toterrp = number_format($toterr / $tottotal * 100, 1, ".", "");
					$totcpu = number_format($totcpu/3600, 2, ".", "");
					$totwall = number_format($totwall/3600, 2, ".", "");
					echo "</tbody>
					<tfoot>
						<th class=firstcol>TOTAL</th>
						<th>$tottotal</th>
						<th>$totok</th>
						<th>$totokp</th>
						<th>$toterr</th>
						<th>$toterrp</th>
						<th>$totwall</th>
					</tfoot>";
					?>
					</table>
					<br>
				<center>
					<small><i>* CPU and Wall times values represented in hours</i></small><br>
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
	include("$sge_path/footer.php");
?>
</body>
</html>
