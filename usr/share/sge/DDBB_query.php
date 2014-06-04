<?php
	// GET URL VALUES
	@$type = $_GET['type'];
	@$period = $_GET['period'];

	if (!isset($period)) {
		// Set default period
		$period = date('Y-m', time());
	}

	//There is no Advcanced search (yet).
	// If search is not advanced, use period as set some lines up
	// Title to be printed
	$tdate = "in <i>".date('F Y', strtotime($period))."</i>";
	// Date part of the query
	$datequery = "date between '".($period."-1")."' and (last_day('".($period."-1")."'))";
	print $calling_script;

	//defualt view has no type	
	if (!isset($type)) {
		$tfirstcol = "Group";
		$onchange = "location.href='$calling_script?period='+this.value";
		// onchange event
		switch ($calling_script) {
			case "sge.php" :
				$ttittle="SGE accounting";
				$query = Account::all(array("select" => "groupname, COUNT(*) as jobs, SUM(ru_wallclock) as cpu_time, SUM(IF(exit_status = 0,1,0)) as ok_jobs", "conditions" => "$datequery", "group" => "groupname"));
				break;
			case "sge_eff.php" :
				$ttittle= "Job efficiency";
				$eff_query = Efficiency::find_by_sql("(select groupname,count(*) as total_jobs,ROUND(avg(mem_eff),2) as mem_avg,ROUND(avg(time_resource_eff),2) as time_avg from account where $datequery and exit_status='0' group by groupname)");
				#$mem_eff_query= Efficiency::find_by_sql("(SELECT mem_eff,groupname,count(*) as number_jobs from account where $datequery and exit_status='0'  group by mem_eff,groupname)");
			#	$time_eff_query= Efficiency::find_by_sql("(SELECT time_resource_eff,groupname,count(*) as number_jobs from account where $datequery and exit_status='0'  group by time_resource_eff,groupname)");
				break;
			case "sge_billing.php" :
				$ttittle= "Cluster usage";
				$query = Account::find_by_sql("(SELECT 'dedicated',groupname, COUNT(*) as jobs, SUM(ru_wallclock) as wallclock, SUM(IF(exit_status = 0,1,0)) as ok_jobs   FROM account WHERE $datequery and qname like '%-el6' GROUP BY groupname) UNION ALL (SELECT 'shared',groupname, COUNT(*) as jobs, SUM(ru_wallclock) as wallclock,  SUM(IF(exit_status = 0,1,0)) as ok_jobs   FROM account WHERE $datequery and qname not like '%-el6' GROUP BY groupname)");
				break;
		}
	}elseif ($type == 'group' ){
		$groupname = $_GET['groupname'];
		$tfirstcol = "User";
		$onchange = "location.href='$calling_script?type=group&groupname=$groupname&period='+this.value";
		switch ($calling_script) {
			case "sge.php" :
				$ttittle= "SGE <i>$groupname</i> detailed accounting";
				$query = Account::all(array("select" => "owner, COUNT(*) as jobs, SUM(ru_wallclock) as cpu_time, SUM(IF(exit_status = 0, 1, 0)) as ok_jobs", "conditions" => "$datequery AND groupname='$groupname'", "group" => "owner"));
				break;
			case "sge_eff.php" :
				$ttittle= "<i>$groupname</i> Job efficiency";
				$eff_query = Efficiency::find_by_sql("(select owner,count(*) as total_jobs,ROUND(avg(mem_eff),2) as mem_avg,ROUND(avg(time_resource_eff),2) as time_avg from account where $datequery and groupname='$groupname' and exit_status='0' group by owner)");
				#$mem_eff_query = Efficiency::find_by_sql("(select mem_eff,owner,count(*) as number_jobs from account  where $datequery  and exit_status='0'  and groupname='$groupname' group by mem_eff,owner)");
				#$time_eff_query = Efficiency::find_by_sql("(select time_resource_eff,owner,count(*) as number_jobs from account where $datequery  and exit_status='0'  and groupname='$groupname' group by time_resource_eff,owner)");
				break;
			case "sge_billing.php" :
				$ttittle= "<i>$groupname</i> Cluster usage";
				$query = Account::find_by_sql("(SELECT 'dedicated',owner, COUNT(*) as jobs, SUM(ru_wallclock) as wallclock, SUM(IF(exit_status = 0,1,0)) as ok_jobs   FROM `account` WHERE $datequery and qname like '%-el6' and groupname='$groupname' GROUP BY owner) UNION ALL (SELECT 'shared',owner, COUNT(*) as jobs, SUM(ru_wallclock) as wallclock, SUM(IF(exit_status = 0,1,0)) as ok_jobs FROM account WHERE $datequery and qname not like '%-el6' and groupname='$groupname' GROUP BY owner)");
				break;
		}
	}
?>
