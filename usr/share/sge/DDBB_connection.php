<?php
	$sge_set_path=(dirname(__FILE__));
        $script=$_SERVER["SCRIPT_NAME"];
        $break = explode('/', $script);
        $calling_script=$break[count($break)-1];

	require_once("$sge_set_path/activerecord/ActiveRecord.php");
	
	// initialize ActiveRecord
	// change the connection settings to whatever is appropriate for your mysql server 
	switch ($calling_script) {
		default :
			// Default file used for connecting to DDBB
			$ini_file="/etc/sge_parser/sge_parser.cfg";
			break;
	}

	ActiveRecord\Config::initialize(function($cfg) use ($ini_file) {

	$conf = parse_ini_file("$ini_file");
	    	
	$cfg->set_model_directory("activerecord/models");
	try
	{
		$cfg->set_connections(array( 'development' => 'mysql://'.$conf['db_user'].':'.$conf['db_pass'].'@'.$conf['db_host'].'/'.$conf['db_name'].''));
	}
	catch (ActiveRecord\DatabaseException $e)
	{
		echo "Database error";
	}
	catch (ActiveRecord\ConfigException $e)
	{
		echo "Config error";
	}
	});
?>
