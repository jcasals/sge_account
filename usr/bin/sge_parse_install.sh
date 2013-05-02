#!/bin/bash

SETUP="sge_parser.cfg"
#MYSQL='/usr/bin/mysql'
MYSQL='mysql'

# LOAD CONFIG PARAMETERS
source $SETUP

if [[ $db_user != "" && $db_pass != "" && $db_host != "" && $db_name != "" ]]; then
	echo ""
	echo "Database setup parameters OK"
	echo ""
else
	echo ""
	echo "ERROR! It seems that there's something wrong with your database setup parameters"
	echo "Please, check the file sge_parser.cfg"
	echo ""
fi

# Check for database;
$MYSQL -u $db_user -p$db_pass -e "SHOW DATABASES LIKE '$db_name';" | grep $db_name >/dev/null

if [ $? -eq '0' ]; then
	echo "Database $db_name already exists. Not creating a new one"
	echo "Aborting installation..."
else
	# creating database
	echo "Creating database $db_name..."
	echo "You must enter MySQL root password"
	$MYSQL -u root -p -e "CREATE DATABASE $db_name";

	if [ $? -ne 0 ]
	then
		echo "Problems creating Database"
		echo "Aborting installation..."
		exit 1
	fi
	echo "Database created!"
	echo""
	
	echo "Granting perms to user $db_user for database $db_name..."
	echo "You must enter MySQL root password again"
	$MYSQL -u root -p -e "GRANT all on $db_name.* to '$db_user'@'$db_host' identified by '$db_pass'" 2>&1;
	
	if [ $? -ne 0 ]; then
		echo "Problems granting permissions to user $db_user for database $db_name"
		echo "Aborting installation..."
		exit 1
	fi
	
	echo "Creating table Account..."
	$MYSQL -u $db_user -p$db_pass -h $db_host -e "USE $db_name;CREATE TABLE account (id_job VARCHAR(15) NOT NULL PRIMARY KEY,date DATE NOT NULL,qname VARCHAR(100) NOT NULL,hostname VARCHAR(100) NOT NULL,groupname VARCHAR(100) NOT NULL,owner VARCHAR(100) NOT NULL,job_name VARCHAR(100) NOT NULL,job_number INT(12) NOT NULL,account VARCHAR(100) NOT NULL,priority INT(5) NOT NULL,submission_time INT(15) NOT NULL,start_time INT(15) NOT NULL,end_time INT(15) NOT NULL,failed INT(4) NOT NULL,exit_status INT(4) NOT NULL,ru_wallclock INT(15) NOT NULL,ru_utime FLOAT(9,5) NOT NULL,ru_stime FLOAT(9,5) NOT NULL,ru_maxrss FLOAT(9,5) NOT NULL,ru_ixrss INT(15) NOT NULL,ru_ismrss INT(15) NOT NULL,ru_idrss INT(15) NOT NULL,ru_isrss INT(15) NOT NULL,ru_minflt INT(15) NOT NULL,ru_majflt INT(15) NOT NULL,ru_nswap INT(15) NOT NULL,ru_inblock FLOAT(9,5) NOT NULL,ru_oublock FLOAT(9,5) NOT NULL,ru_msgsnd INT(15) NOT NULL,ru_msgrcv INT(15) NOT NULL,ru_nsignals INT(15) NOT NULL,ru_nvcsw INT(15) NOT NULL,ru_nivcsw INT(15) NOT NULL,project VARCHAR(100) NOT NULL,department VARCHAR(100) NOT NULL,granted_pe VARCHAR(100) NOT NULL,slots int(5) NOT NULL,task_number int(5) NOT NULL,cpu FLOAT(9,5) NOT NULL,mem FLOAT(9,5) NOT NULL,io FLOAT(9,5) NOT NULL,category TEXT NOT NULL,iow FLOAT(9,5) NOT NULL,pe_taskid TEXT NOT NULL,maxvmem FLOAT(15,5) NOT NULL,arid int(2) NOT NULL,ar_submission_time INT(15) NOT NULL); CREATE INDEX date_index on account (date,ru_wallclock,groupname,exit_status);"
	echo "Creating table Files..."
	$MYSQL -u $db_user -p$db_pass -h $db_host -e "USE $db_name;CREATE TABLE files (file_name VARCHAR(30) NOT NULL PRIMARY KEY);"
	
	if [ $? -eq 0 ]; then
		echo ""
		echo "Hooray! The database has been created correctly!"
		echo "Installation finished!"
	else
		echo "Problems creating tables in $db_name."
		echo "Aborting installation..."
		exit 1
	fi
fi
