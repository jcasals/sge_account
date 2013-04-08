#!/bin/bash

SETUP="../setup"

# MAC OS X
if [[ `system_profiler SPSoftwareDataType | grep "System Version" | grep "OS X"` != "" ]]; then
	yesterday`date -v-1d +%Y%m%d`
# OTHER (LINUX)
else
	yesterday=`date '+%Y%m%d' -d "yesterday"`
fi

# SGE LOGS PARAMETERS
SGE_LOGS=`cat $SETUP/config.ini | grep sge_logs | cut -d'=' -f2`

# DATABASE PARAMETERS
db_user=`cat $SETUP/config.ini | grep db_user | cut -d'=' -f2`
db_pass=`cat $SETUP/config.ini | grep db_pass | cut -d'=' -f2`
db_host=`cat $SETUP/config.ini | grep db_host | cut -d'=' -f2`
db_name=`cat $SETUP/config.ini | grep db_name | cut -d'=' -f2`

while read line; do
	OLD_IFS="$IFS"
	IFS=":"

	sgeArr=($line)
	
	job_date=`date -r ${sgeArr[8]} '+%Y-%m-%d'`
	
	/usr/local/mysql/bin/mysql -u $db_user -p$db_pass -h $db_host $db_name -e "INSERT INTO account (date, qname, hostname, groupname, owner, job_name, job_number, account, priority, submission_time, start_time, end_time, failed, exit_status, ru_wallclock, ru_utime, ru_stime, ru_maxrss, ru_ixrss, ru_ismrss, ru_idrss, ru_isrss, ru_minflt, ru_majflt, ru_nswap, ru_inblock, ru_oublock, ru_msgsnd, ru_msgrcv, ru_nsignals, ru_nvcsw, ru_nivcsw, project, department, granted_pe, slots, task_number, cpu, mem, io, category, iow, pe_taskid, maxvmem, arid, ar_submission_time) VALUES ('$job_date', '${sgeArr[0]}', '${sgeArr[1]}', '${sgeArr[2]}', '${sgeArr[3]}', '${sgeArr[4]}', '${sgeArr[5]}', '${sgeArr[6]}', '${sgeArr[7]}', '${sgeArr[8]}', '${sgeArr[9]}', '${sgeArr[10]}', '${sgeArr[11]}', '${sgeArr[12]}', '${sgeArr[13]}', '${sgeArr[14]}', '${sgeArr[15]}', '${sgeArr[16]}', '${sgeArr[17]}', '${sgeArr[18]}', '${sgeArr[19]}', '${sgeArr[20]}', '${sgeArr[21]}', '${sgeArr[22]}', '${sgeArr[23]}', '${sgeArr[24]}', '${sgeArr[25]}', '${sgeArr[26]}', '${sgeArr[27]}', '${sgeArr[28]}', '${sgeArr[29]}', '${sgeArr[30]}', '${sgeArr[31]}', '${sgeArr[32]}', '${sgeArr[33]}', '${sgeArr[34]}', '${sgeArr[35]}', '${sgeArr[36]}', '${sgeArr[37]}', '${sgeArr[38]}', '${sgeArr[39]}', '${sgeArr[40]}', '${sgeArr[41]}', '${sgeArr[42]}', '${sgeArr[43]}', '${sgeArr[44]}')"
	
	IFS="$OLD_IFS"	
done < $SGE_LOGS/$yesterday
