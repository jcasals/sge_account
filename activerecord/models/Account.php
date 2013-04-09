<?php
	class Account extends ActiveRecord\Model {
		static $primary_key = 'id_job';
		static $table_name = 'account'; // Por defecto espera que las tablas sean en plural, esta es en singular
	}
?>