<?php
	class Account extends ActiveRecord\Model {
		//static $primary_key = array('date','storage_group','library','file_family');
		static $table_name = 'account'; // Por defecto espera que las tablas sean en plural, esta es en singular
	}
?>