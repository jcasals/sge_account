<?php
	// GET URL VALUES
	@$type = $_GET['type'];
	@$period = $_GET['period'];
	@$search = isset($_POST['advanced']);
	
	if (!isset($period)) {
	    $period = date('Y-m', time());
	    $periodq = $period."-1";
	}
	else {
		$period = $period;
	    $periodq = $period."-1";
	}
	
	switch ($search) {
	    case "1":
	    	// If search is advanced, get POST date values from and to
	        $from = $_POST['from'];
	    	$to = $_POST['to'];
	    	// Title to be printed
	    	$tdate = "from <i>$from</i> to <i>$to</i>";
	    	// Date part of the query
	    	$datequery = "date between '$from' and '$to'";
	    	// Load date values. This must be here and not on the js file.
			echo "<script>$(document).ready(function() { $('#from').val('$from'); $('#to').val('$to'); $('#advanced').show(); $('#monthly').hide(); });</script>";
	        break;
		default:
			// If search is not advanced, use period as set some lines up
			// Title to be printed
			$tdate = "in <i>".date('F Y', strtotime($period))."</i>";
			// Date part of the query
	    	$datequery = "date between '$periodq' and (last_day('$periodq'))";
	    	// Load date values. This must be here and not on the js file.
			echo "<script>$(document).ready(function() { $('#period option[value=".$period."]').attr('selected', true); $('#advanced').hide(); $('#monthly').show(); });</script>";				
	}
	
	switch ($type) {
	    case "g":
	    	// Type is the detailed group name info
	        @$groupname = $_GET['groupname'];
	        if ($groupname == "") { header("Location: index.php"); }
	        $tfirst = "<i>$groupname</i> data";
	        // Table left upper label
	        $firstcolt = "Owner";
	        // onchange event
	        $onchange = "location.href='index.php?type=g&groupname=$groupname&period='+this.value";
	        // Query for group type
			$query = Account::all(array("select" => "owner, COUNT(*) as jobs, SUM(ru_wallclock) as cpu_time, SUM(IF(exit_status = 0, 1, 0)) as ok_jobs", "conditions" => "$datequery AND groupname='$groupname'", "group" => "owner"));
	        break;
		default:
			// General or, if other, go to general
			$tfirst = "General data";
			// Table left upper label
			$firstcolt = "Group";
			// onchange event
	        $onchange = "location.href='index.php?period='+this.value";
			// Query for all type
			$query = Account::all(array("select" => "groupname, COUNT(*) as jobs, SUM(ru_wallclock) as cpu_time, SUM(IF(exit_status = 0,1,0)) as ok_jobs", "conditions" => "$datequery", "group" => "groupname"));
	}
?>