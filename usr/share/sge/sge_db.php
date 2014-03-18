<?php
	$path=(dirname(__FILE__));
	require_once("$path/activerecord/ActiveRecord.php");

	// initialize ActiveRecord
	// change the connection settings to whatever is appropriate for your mysql server 
	ActiveRecord\Config::initialize(function($cfg)
	{
		$conf = parse_ini_file('/etc/sge_parser/sge_parser.cfg');
		$cfg->set_model_directory('activerecord/models');
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