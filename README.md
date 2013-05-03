Description
===========

A Sun Grid Engine accounting system website done with PHP (using Twitter Bootstrap for UI), MySQL (with PHP ActiveRecord as ORM) and a Bash script parser.

Requirements
============

- PHP 5.3, PHP-Mysql and PHP-PDO with the MySQL extension enabled
- MySQL
- Apache (httpd) server

Installation
============

1. Download the ZIP package and put everything in its right path
2. Go to file <b>/etc/sge_parser.cfg</b> and specify your database parameters in the <i>DB_Config</i> section. If you are running this in localhost, you just have to specify your <i>db_user</i> and <i>db_pass</i> parameters. The user and database name you specified will be created with the installation script.
3. Check your sge logs location and specify it in the <b>/etc/sge_parser.cfg</b> file in the <i>SGE_Config</i> section. This is important for the log parser to be able to read it and insert the data in the database.
4. If you are sure you put the correct values, run the <b>/usr/sge_parse_install.sh</b> script and the database will be created. You will be asked for database root password twice during the installation.
5. Remember that you must add the parser <b>/usr/bin/sge_parse.sh</b> to your preferred cron system. We recommend to put it at <b>00:01AM</b> to have a good insertion of the data in the database.

Content
=======

sge_account
	|
	|-- etc
	|	 |-- httpd
	|	 |	  |-- conf.d
	|	 |			  |-- accounting.conf
	|	 |
	|	 |-- logrotate.d
	|	 |	  |-- sge
	|	 |
	|	 |-- sge_parser
	|		  |-- sge_parser.cfg
	|		 		 
	|-- usr
		 |-- bin
		 |	  |-- sge_parse_install.sh
		 |	  |-- sge_parse.sh
		 |
		 |-- share
		 	  |-- sge
		 	  	   |-- activerecord
		 	  	   |-- css
		 	  	   |-- img
		 	  	   |-- js
		 	  	   |-- index.php
		 	  	   |-- head.php
		 	  	   |-- header.php
		 	  	   |-- footer.php
		 	  	   |-- set.php
		 	  	   |-- db.php

Tools used
==========

- Twitter Bootstrap (CSS, JS and HTML)
- jQuery and jQuery UI
- Datatables (Bootstrap implementation)
- Raphael JS (raphael.js, g.raphael.js, g.pie.js)
- PHPActiveRecord as a database ORM

Licensing
=========


Important
=========

This is a beta version. Things may not work as expected or as you think it should work. For any doubt, advice or complaint, please tell me and I will try to fix it as soon as possible.
