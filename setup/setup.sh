#!/bin/bash

SETUP="."

# CATCHING DATABASE PARAMETERS
db_user=`cat $SETUP/config.ini | grep db_user | cut -d'=' -f2`
db_pass=`cat $SETUP/config.ini | grep db_pass | cut -d'=' -f2`
db_host=`cat $SETUP/config.ini | grep db_host | cut -d'=' -f2`
db_name=`cat $SETUP/config.ini | grep db_name | cut -d'=' -f2`

if [[ $db_user != "" && $db_pass != "" && $db_host != "" && $db_name != "" ]]; then
	echo ""
	echo "Database setup parameters OK"
	echo ""
else
	echo ""
	echo "ERROR! It seems that there's something wrong with your database setup parameters"
	echo "Please, check the file setup/config.ini"
	echo ""
fi

# CREATING DATABASE AND TABLE
mysql -u $db_user -p$db_pass -h $db_host -e "CREATE DATABASE $db_name;USE $db_name;CREATE TABLE account (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,date DATE NOT NULL,qname VARCHAR(100) NOT NULL,hostname VARCHAR(100) NOT NULL,groupname VARCHAR(100) NOT NULL,owner VARCHAR(100) NOT NULL,job_name VARCHAR(100) NOT NULL,job_number INT(12) NOT NULL,account VARCHAR(100) NOT NULL,priority INT(5) NOT NULL,submission_time INT(15) NOT NULL,start_time INT(15) NOT NULL,end_time INT(15) NOT NULL,failed INT(4) NOT NULL,exit_status INT(4) NOT NULL,ru_wallclock INT(15) NOT NULL,ru_utime FLOAT(9,5) NOT NULL,ru_stime FLOAT(9,5) NOT NULL,ru_maxrss FLOAT(9,5) NOT NULL,ru_ixrss INT(15) NOT NULL,ru_ismrss INT(15) NOT NULL,ru_idrss INT(15) NOT NULL,ru_isrss INT(15) NOT NULL,ru_minflt INT(15) NOT NULL,ru_majflt INT(15) NOT NULL,ru_nswap INT(15) NOT NULL,ru_inblock FLOAT(9,5) NOT NULL,ru_oublock FLOAT(9,5) NOT NULL,ru_msgsnd INT(15) NOT NULL,ru_msgrcv INT(15) NOT NULL,ru_nsignals INT(15) NOT NULL,ru_nvcsw INT(15) NOT NULL,ru_nivcsw INT(15) NOT NULL,project VARCHAR(100) NOT NULL,department VARCHAR(100) NOT NULL,granted_pe VARCHAR(100) NOT NULL,slots int(5) NOT NULL,task_number int(5) NOT NULL,cpu FLOAT(9,5) NOT NULL,mem FLOAT(9,5) NOT NULL,io FLOAT(9,5) NOT NULL,category TEXT NOT NULL,iow FLOAT(9,5) NOT NULL,pe_taskid TEXT NOT NULL,maxvmem FLOAT(15,5) NOT NULL,arid int(2) NOT NULL,ar_submission_time INT(15) NOT NULL);"

# REMEMBER TO ADD THE PARSING SCRIPT CRON
echo "Hooray! The database has been created correctly, you're almost done!"
echo 'REMEMBER: You must add the script "scripts/parse.sh" to your daily cron, running at 00:01AM to use the accounting correctly.'
