#!/usr/bin/perl -w

use strict;
use Data::Dumper;
use Config::Simple;
use Getopt::Long;
use POSIX qw(strftime);
use DBI;
use Log::Dispatch;
use Log::Dispatch::File;
use Sys::Hostname;
use File::Basename;


use constant LOG_DIR    => '/var/log/sge_accounting/';
use constant LOG_FILE   => 'accounting.log';

my ($help,$man);
GetOptions (
        'version|V'     => sub { VersionMessage() ; exit 0},
        'help|h'        => \$help,
        'man'           => \$man,
) or  pod2usage(2);

pod2usage(1) if $help;
pod2usage(-verbose => 2) if $man;


# LOAD CONFIG PARAMETERS
my %Config=();
Config::Simple->import_from('/etc/sge_parser/sge_parser.cfg',\%Config);
#print Dumper \%Config;

# Logging
my $HOSTNAME = hostname;
my $log = Log::Dispatch->new();
$log->add(
        Log::Dispatch::File->new(
                callbacks => sub { my %h=@_; return scalar localtime(time)." ".$HOSTNAME." $0\[$$]: ".$h{message}."\n"; },
                mode      => 'append',
                name      => 'logfile',
                min_level => "$Config{'Log.level'}",
                filename  => LOG_DIR."/".LOG_FILE,
        )
);

my $dbh = DBI->connect("DBI:mysql:$Config{'DB_Config.db_name'}", "$Config{'DB_Config.db_user'}", "$Config{'DB_Config.db_pass'}", {AutoCommit=>0,RaiseError=>0}) || die $log->error("Cannot connect to $Config{'DB_Config.db_name'} as $Config{'DB_Config.db_user'} using passwd. Please check conf");

$log->info("Starting SGE account file parsing process");
$log->error("Cannot read file /etc/sge_parser/sge_parser.cfg . Exiting") unless (-r "/etc/sge_parser/sge_parser.cfg");


# 1) look for files: We load all files to parse into an array and parse all the array.
# if input file provided, the array will have 1 filed, if not,
# if there's no input file, we look for all accounting files under $Config{'SGE_Log.sge_logs'} and load all into array

my @all_file_to_parse;

if (!defined($ARGV[0])) {
	@all_file_to_parse=<$Config{'SGE_Log.sge_logs'}accounting-*.gz>;
}else{
	unless (-e $ARGV[0]) {
	print "File $ARGV[0] does not exists, exiting\n";
	$log->error("File $ARGV[0] does not exists, exiting");
	exit 0;
	}
	$all_file_to_parse[0]="$ARGV[0]";
}

foreach my $file_to_parse (@all_file_to_parse) {
	my $MySQL_file_name=fileparse("$file_to_parse");
	$log->info("File to parse: $file_to_parse ($MySQL_file_name)");
	# 2) check if parsed
		my $sth1=$dbh->prepare("SELECT exists( SELECT file_name FROM files WHERE file_name='$MySQL_file_name')");
		my @row_array=$dbh->selectrow_array($sth1); # (it returns 1 i the file has been already parsed 0 if not)
		if ( $row_array[0] ) {
			$log->info("The file $file_to_parse has benn already parsed");
			$log->info("If you want to reparse it, run the MySQL command:\n mysql>  delete from files where file_name=$MySQL_file_name # And rerun this script");
			print "The file $file_to_parse has benn already parsed\nIf you want to reparse it, run the MySQL command:\n mysql>  delete from files where file_name=$MySQL_file_name\nAnd rerun this script\n";
		}else{
			# We must parse the file and upload data to Mysql
			# Preparing MySQL query (51)
			my $sth2=$dbh->prepare("INSERT IGNORE INTO account (id_job ,date ,qname ,hostname ,groupname ,owner ,job_name ,job_number ,account ,priority ,submission_time ,start_time ,end_time ,failed ,exit_status ,ru_wallclock ,ru_utime ,ru_stime ,ru_maxrss ,ru_ixrss ,ru_ismrss ,ru_idrss ,ru_isrss ,ru_minflt ,ru_majflt ,ru_nswap ,ru_inblock ,ru_oublock ,ru_msgsnd ,ru_msgrcv ,ru_nsignals ,ru_nvcsw ,ru_nivcsw ,project ,department ,granted_pe ,slots ,task_number ,cpu ,mem ,io ,category ,iow ,pe_taskid ,maxvmem ,arid ,ar_submission_time ,requested_time ,requested_mem ,time_eff,time_resource_eff,mem_eff,valid) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")or die $dbh->errst;
			my $sth3=$dbh->prepare("INSERT INTO files (file_name) VALUES (?)");
			open  (ACCOUNTING_FILE, "/usr/bin/gzip -cd $file_to_parse|") || die "Problems opening file: $!";
			while (<ACCOUNTING_FILE>) {
				my $valid='1';
				# Each line MUST have all the  values from (man (5) sge_accounting). We just split all fileds by ":" :
				my ($qname ,$hostname ,$groupname ,$owner ,$job_name ,$job_number ,$account ,$priority ,$submission_time ,$start_time ,$end_time ,$failed ,$exit_status ,$ru_wallclock ,$ru_utime ,$ru_stime ,$ru_maxrss ,$ru_ixrss ,$ru_ismrss ,$ru_idrss ,$ru_isrss ,$ru_minflt ,$ru_majflt ,$ru_nswap ,$ru_inblock ,$ru_oublock ,$ru_msgsnd ,$ru_msgrcv ,$ru_nsignals ,$ru_nvcsw ,$ru_nivcsw ,$project ,$department ,$granted_pe ,$slots ,$task_number ,$cpu ,$mem ,$io ,$category ,$iow ,$pe_taskid ,$maxvmem ,$arid ,$ar_submission_time )=(split /:/);
				chomp($ar_submission_time);
				my ($time_eff,$time_resource_eff,$mem_eff);
				#	 And we create 7 more fields:  id_job - date - requested_time - requested_mem - time_eff - time_resource_eff - mem_eff
				#			 id_job=job-task
				#			 date=$end_time in YYYYMMDD format
				#			 requested_time = h_rt value
				#			 requested_mem = h_vmem (* #slots)
				#			 time_eff = (ru_wallclock/(ru_utime+ru_stime)) refers to time eff (walltime/(used time sys +user))
				#			 time_resource_eff = (h_rt/ru_wallclock) refers to requested time vs used time (walltime)
				#			 mem_eff = h_vmem/maxvmem
				my $id_job="$job_number-$task_number";
				my $date=strftime ("%Y%m%d",localtime($end_time));
				# Some jobs could have problems:
					# not have a valid starttime (0): killed when qw 
					# empty slots
					# empty requetsed resources (strange)
					# very short job: ru_wallclock = 0
				$_=$category;
				my ($requested_time,$requested_mem,$unit) = /h_rt=(\d+).*h_vmem=(\d+|\d+\.\d+)(\w)/;
		
				if ( ($start_time != 0 ) &&  (defined($requested_time)) && (defined($requested_mem)) && ($slots != "0" ) && ($ru_wallclock != "0")) {
					#Looks like valid entry ->starttime>0, requested reosurces defined and slots >0;
					$valid='1';
					# from bytes to GB
					$maxvmem = sprintf "%.2f",($maxvmem /= 1048576);
					if ($unit eq "G") {
						$requested_mem= ($requested_mem*$slots*1024);
					}else{
						$requested_mem = ($requested_mem*$slots*1024*1024);
					}
					$time_eff=sprintf "%.2f",((((int $ru_utime)+(int $ru_stime))/$ru_wallclock)*100);
					$time_resource_eff=sprintf "%.2f",(($ru_wallclock/$requested_time)*100);
					$mem_eff=sprintf "%.2f",(($maxvmem/$requested_mem)*100);
					# Real wallclock = ru_wallclock*slots
					$ru_wallclock=($ru_wallclock*$slots);
					$log->debug("Job $id_job [$date] parsed :  REQ_TIME : $requested_time ; REQ_MEM : $requested_mem ; UNIT : $unit ; endtime: $end_time; starttime : $start_time ; TIME_EFF : $time_eff ; TIME_RESO_EFF : $time_resource_eff ; MEM_EFF : $mem_eff");
				}else{
					# Strange jobs do not have valid values like wallclock, requested reosurces, etc... so dont try to parse them cause they could generate (and they do) srange values. Just log each job and the cause why it's starnge.
					$valid='0';
					$time_eff=0;
					$time_resource_eff=0;
					$mem_eff=0;
					if (defined($maxvmem)){
						$maxvmem = sprintf "%.2f",($maxvmem /= 1048576);
					}
					if (defined($requested_mem) && ($slots != "0" )){
						if ($unit eq "G") {
							$requested_mem= ($requested_mem*$slots*1024);
						}else{
						$requested_mem = ($requested_mem*$slots*1024*1024);
						}
					}
							
					if ($start_time == '0'){
						# not have a valid starttime (0): killed when qw 
						$log->info("Job $id_job [$date] was killed before starting: starttime=$start_time");
						$log->debug("Job $id_job [$date] parsed :  REQ_TIME : $requested_time ; REQ_MEM : $requested_mem ; UNIT : $unit ; endtime: $end_time; starttime : $start_time ; TIME_EFF : $time_eff ; TIME_RESO_EFF : $time_resource_eff ; MEM_EFF : $mem_eff");
					}elsif ($slots  == '0'){
						# empty slots we don't want to parse ths job. could be corrupted.
						$log->info("Job $id_job [$date] has no slots: \'$category\'");
						$log->debug("Job $id_job [$date] parsed :  REQ_TIME : $requested_time ; REQ_MEM : $requested_mem ; UNIT : $unit ; endtime: $end_time; starttime : $start_time ; TIME_EFF : $time_eff ; TIME_RESO_EFF : $time_resource_eff ; MEM_EFF : $mem_eff");
					}elsif ($ru_wallclock== '0'){
						# wallclockas it did not use any time, let's says it did not use any resource:
						$log->info("Job $id_job [$date] really short job, wallclock : $ru_wallclock : $time_eff,$time_resource_eff ");
						$log->debug("Job $id_job [$date] parsed :  REQ_TIME : $requested_time ; REQ_MEM : $requested_mem ; UNIT : $unit ; endtime: $end_time; starttime : $start_time ; TIME_EFF : $time_eff ; TIME_RESO_EFF : $time_resource_eff ; MEM_EFF : $mem_eff");
					}else{
						# empty requetsed resources (strange)
						$log->info("Job $id_job [$date] did not ask for resources: \'$category\'");
						$log->debug("Job $id_job [$date] parsed :  REQ_TIME : $requested_time ; REQ_MEM : $requested_mem ; UNIT : $unit ; endtime: $end_time; starttime : $start_time ; TIME_EFF : $time_eff ; TIME_RESO_EFF : $time_resource_eff ; MEM_EFF : $mem_eff");
					}
				}	
				# 6) update databases with parsed file
					$sth2->execute($id_job ,$date ,$qname ,$hostname ,$groupname ,$owner ,$job_name ,$job_number ,$account ,$priority ,$submission_time ,$start_time ,$end_time ,$failed ,$exit_status ,$ru_wallclock ,$ru_utime ,$ru_stime ,$ru_maxrss ,$ru_ixrss ,$ru_ismrss ,$ru_idrss ,$ru_isrss ,$ru_minflt ,$ru_majflt ,$ru_nswap ,$ru_inblock ,$ru_oublock ,$ru_msgsnd ,$ru_msgrcv ,$ru_nsignals ,$ru_nvcsw ,$ru_nivcsw ,$project ,$department ,$granted_pe ,$slots ,$task_number ,$cpu ,$mem ,$io ,$category ,$iow ,$pe_taskid ,$maxvmem ,$arid ,$ar_submission_time ,$requested_time ,$requested_mem ,$time_eff ,$time_resource_eff ,$mem_eff,$valid)or die $dbh->errstr;	
			}
			close(ACCOUNTING_FILE);
			$sth3->execute($MySQL_file_name);
			$dbh->commit or die $dbh->errstr;
			$log->info("Finished succesfully parsing $file_to_parse");
	}
}
			$dbh->disconnect();


sub VersionMessage {
        print "version: 1.0.1\n";
}
