#!/usr/bin/perl -w

use strict;

use Config::Simple;
use DBI;


# LOAD CONFIG PARAMETERS
my %Config=();
Config::Simple->import_from('../sge_parser.cfg',\%Config);

my $dbh = DBI->connect("DBI:mysql:$Config{'DB_Config.db_name'}", "$Config{'DB_Config.db_user'}", "$Config{'DB_Config.db_pass'}", {AutoCommit=>0,RaiseError=>0}); #|| die print "Cannot connect to $Config{'DB_Config.db_name'} as $Config{'DB_Config.db_user'} using passwd. Please check conf";

if ($dbh) {
	# DDBB exists, raise error message
	print "Seems that I was able to connect to $Config{'DB_Config.db_name'} as $Config{'DB_Config.db_user'} with password.\nI'm not going to create a new database/table with the same information. Please remove the DDBB/table if you really want to run this script.\n\n(Are you sure you want todo that?)\n";	
	$dbh->commit or die $dbh->errstr;
	$dbh->disconnect();
	exit 1;
}else{
	# DDBB does not exists. Let's create it
	print "Going to create DDBB: $Config{'DB_Config.db_name'}\n";
	print "please enter root passwd:\n";
	my $mysql_passwd=<STDIN>;
	chomp ($mysql_passwd);
	# Connect as root
	my $ndbh = DBI->connect("DBI:mysql::$Config{'DB_Config.db_host'}",'root',"$mysql_passwd")|| die print "Cannot connect to $Config{'DB_Config.db_host'} MySQL server as root\n";
	# Create DDBB
	my $rc = $ndbh->func('createdb', "$Config{'DB_Config.db_name'}" , 'admin')||die "I was not able to create $Config{'DB_Config.db_name'} in $Config{'DB_Config.db_host'} as root\nCheck the MySQL part";
	$ndbh->disconnect();
	print "Successfully created $Config{'DB_Config.db_name'}\n";
	# Creating user:
	print "Granting perms to user $Config{'DB_Config.db_user'} for database $Config{'DB_Config.db_name'}...\n";
	# Reconnect to new created DDBB
	$ndbh = DBI->connect("DBI:mysql:$Config{'DB_Config.db_name'}", 'root', "$mysql_passwd");
	my $sth = $ndbh->do("GRANT all on $Config{'DB_Config.db_name'}.* to '$Config{'DB_Config.db_user'}'\@'$Config{'DB_Config.db_host'}' identified by '$Config{'DB_Config.db_pass'}'")|| die print "Problems GRANTING perms to $Config{'DB_Config.db_user'}\n";
	$ndbh->disconnect();
	$ndbh = DBI->connect("DBI:mysql:$Config{'DB_Config.db_name'}", "$Config{'DB_Config.db_user'}", "$Config{'DB_Config.db_pass'}")|| die "Print cannot connect as $Config{'DB_Config.db_user'} to $Config{'DB_Config.db_name'}\n";
	#Creating tables:
	my @tables = ( 
			#files table
			"CREATE TABLE files ( file_name varchar(30) NOT NULL, PRIMARY KEY (file_name)) ENGINE=MyISAM DEFAULT CHARSET=latin1",
			"CREATE TABLE account (
			id_job varchar(15) NOT NULL,
			date date NOT NULL,
			qname varchar(25) NOT NULL,
			hostname varchar(35) NOT NULL,
			groupname varchar(50) NOT NULL,
			owner varchar(30) NOT NULL,
			job_name varchar(100) NOT NULL,
			job_number int(9) NOT NULL,
			account varchar(50) NOT NULL,
			priority int(1) NOT NULL,
			submission_time int(15) NOT NULL,
			start_time int(15) NOT NULL,
			end_time int(15) NOT NULL,
			failed int(4) NOT NULL,
			exit_status int(4) NOT NULL,
			ru_wallclock int(8) NOT NULL,
			ru_utime float(8,2) NOT NULL,
			ru_stime float(8,2) NOT NULL,
			ru_maxrss float(12,2) NOT NULL,
			ru_ixrss int(12) NOT NULL,
			ru_ismrss int(12) NOT NULL,
			ru_idrss int(12) NOT NULL,
			ru_isrss int(12) NOT NULL,
			ru_minflt int(12) NOT NULL,
			ru_majflt int(12) NOT NULL,
			ru_nswap int(12) NOT NULL,
			ru_inblock float(8,2) NOT NULL,
			ru_oublock float(8,2) NOT NULL,
			ru_msgsnd int(12) NOT NULL,
			ru_msgrcv int(12) NOT NULL,
			ru_nsignals int(12) NOT NULL,
			ru_nvcsw int(12) NOT NULL,
			ru_nivcsw int(12) NOT NULL,
			project varchar(10) NOT NULL,
			department varchar(20) NOT NULL,
			granted_pe varchar(8) NOT NULL,
			slots int(3) NOT NULL,
			task_number int(7) NOT NULL,
			cpu float(8,2) NOT NULL,
			mem float(10,2) NOT NULL,
			io float(8,2) NOT NULL,
			category text NOT NULL,
			iow float(10,5) NOT NULL,
			pe_taskid varchar(4) NOT NULL,
			maxvmem float(15,2) NOT NULL,
			arid int(1) NOT NULL,
			ar_submission_time int(1) NOT NULL,
			requested_time int(10) NOT NULL,
			requested_mem float(15,2) NOT NULL,
			time_eff float(15,2) NOT NULL,
			time_resource_eff float(15,2) NOT NULL,
			mem_eff float(15,2) NOT NULL,
			valid bool NOT NULL,
			PRIMARY KEY (id_job),
			KEY `group_accounting` (`date`,`ru_wallclock`,`groupname`,`exit_status`),
			KEY `user_accounting` (`date`,`ru_wallclock`,`owner`,`groupname`,`exit_status`),
			KEY `group_eff` (`date`,`mem_eff`,`time_resource_eff`,`groupname`,`exit_status`),
			KEY `user_eff` (`date`,`mem_eff`,`time_resource_eff`,`owner`,`groupname`,`exit_status`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1");
		for my $sql(@tables){
			$sth = $ndbh->do($sql);
		}
	print "Successfuly created all tables\nExiting!!\n\n";
	$ndbh->disconnect();
}
