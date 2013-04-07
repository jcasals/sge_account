<!DOCTYPE html>
<html lang="en">
<head>
	<?php
        include('head.php');
		include('db.php');

		@$period = $_GET['period'];
		if (!isset($period)) { 
			$period = date("Y-m");
		}
		// THIS LINE MUST BE HERE
		echo "<script>$(document).ready(function() { $('#period option[value=".$period."]').attr('selected', true) });</script>";
		
		@$groupname = $_GET['groupname'];
		if ($groupname == "") { header("Location: index.php"); }
		
		// GENERIC QUERIES
		$owners = Account::all(array('select' => "DISTINCT owner", "order" => "groupname ASC", "conditions" => "groupname LIKE '$groupname'"));
    ?>
</head>
<body>
	<?php
        include('header.php');
    ?>
	<div class="container-fluid">
		<div class="row-fluid cos">
			<div class="span12">
				<center>
	                <div class="well taula">
                        <h4>SGE Accounting for <i><?php echo $groupname; ?></i></h4>
						<br>
	                    <div class="pull-right">
	                    	<div id="monthly">
								<select name=period id="period" class='input-medium pull-right' onchange="location.href='group.php?groupname=<?php echo $groupname; ?>&period='+this.value">
									<?php
								        $months = Account::all(array('select' => "DISTINCT date_format(date,'%Y-%m') as date", "order" => "date DESC"));
								        foreach ($months as $m) 
										{
											echo "<option value='".$m->date->format('Y-m')."'>".$m->date->format('F Y')."</option>";
										}
								    ?>
								</select>
								<br>
								<!--<a id="showadv" class="pull-right" href="#"><small>Advanced search</small></a>-->
							</div>
							<div id="advanced" style="display:none">
								<form name="form" id="form" method="post" action="#" enctype="multipart/form-data" class="form-inline">
									From <input name="from" id="from" type="text" class="input-small required" readonly > 
									To <input name="to" id="to" type="text" class="input-small required" readonly > 
									User <select name="user" id="user" type="text" class="input-medium required">
										<option value="">User...</option>
										<?php
											foreach ($owners as $o) 
											{
												echo "<option value=".$o->owner.">".$o->owner."</option>";
											}
										?>
									</select>
									<button type=submit class="btn btn-small">Go!</button>
								</form>
								<!--<a id="showmon" class="pull-right" href="#"><small>Monthly search</small></a>-->
							</div>
						</div>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover taules" id="ctable">
                            <thead>
                                <tr>
                                    <th class=firstcol>Owner</th>
									<th>Total Jobs</th>
									<th>OK Jobs</th>
									<th>OK %</th>
									<th>Error Jobs</th>
									<th>Error %</th>
									<th>CPU Time</th>
									<th>Wall Time</th>
                                </tr>
							</thead>
                            <tbody>
							<?php
								$tottotal = $totok = $totokp = $toterr = $toterrp = $totcpu = $totwall = 0;
								
								foreach ($owners as $o) 
								{
									$owner = $o->owner;
									$a2 = Account::first(array('select' => "COUNT(*) as jobs, SUM(ru_wallclock) as cpu_time, (SUM(end_time)-SUM(submission_time)) as wall_time", 
																"conditions" => "groupname LIKE '$groupname' AND owner LIKE '$owner' AND end_time > 0 AND date LIKE'$period%'"));
                            		$a3 = Account::first(array('select' => "COUNT(*) as ok_jobs", 
                            									"conditions" => "groupname LIKE '$groupname' AND owner LIKE '$owner' AND exit_status = 0 AND date LIKE'$period%'"));

									$jobs = $a2->jobs;
									$ok = $a3->ok_jobs;
									$err = $jobs - $ok;
									$okp = number_format($ok / $jobs * 100, 1, ".", "");
									$errp = number_format($err / $jobs * 100, 1, ".", "");
									$cpu_time = number_format($a2->cpu_time/3600, 2, ".", "");
									$wall_time = number_format($a2->wall_time/3600, 2, ".", "");
                                			
									echo "
										<tr>
											<td class=firstcol>$owner</td>
                        	                <td>$jobs</td>
                        	                <td>$ok</td>
                        	                <td>$okp</td>
                        	                <td>$err</td>
                        	                <td>$errp</td>
                        	                <td>$cpu_time</td>
                        	                <td>$wall_time</td>
										</tr>
									";
																				
									$tottotal += $jobs;
                                    $totok += $ok;
                                    $toterr += $err;
                                    $totcpu += $a2->cpu_time;
                                    $totwall += $a2->wall_time;
								}

								$totokp = number_format($totok / $tottotal * 100, 1, ".", "");
								$toterrp = number_format($toterr / $tottotal * 100, 1, ".", "");
								$totcpu = number_format($totcpu/3600, 2, ".", "");
								$totwall = number_format($totwall/3600, 2, ".", "");
						
								echo "
									</tbody>
									<tfoot>
										<th class=firstcol>TOTAL</th>
										<th>$tottotal</th>
										<th>$totok</th>
										<th>$totokp</th>
										<th>$toterr</th>
										<th>$toterrp</th>
										<th>$totcpu</th>
										<th>$totwall</th>
									</tfoot>
								";
							?>
                        </table>
                        <br>
                        <center>
                        	<small><i>* CPU Time and Wall Time values represented in hours</i></small>
                        </center>
					</div>				
                </center>
			</div><!--/span-->
		</div><!--/row-->
	</div><!--/.fluid-container-->
	<hr>
	<?php
		include('footer.php');
	?>
</body>
</html>