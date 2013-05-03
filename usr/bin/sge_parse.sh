#!/bin/bash

MYSQL='/usr/bin/mysql'
GUNZIP='/bin/gunzip'
GZIP='/bin/gzip'

# LOAD CONFIG PARAMETERS
source /etc/sge_parser/sge_parser.cfg

# We expect the log file to have the format accounting-$date.gz.
# 1) look for files
# 2) check if parsed
# 3) gunzip
# 4) Parse
# 5) gzip again
# 6) update databases with parsed file

# 1) Look for files
for log_file_complete_path in $sge_logs/accounting-*.gz; do
	log_file=$(basename $log_file_complete_path)
	file_name=$(echo $log_file|sed 's/.gz//')

	echo ""
	echo "### STARTING FILE PARSING ###"
	echo "Log file complete path: $log_file_complete_path"
	echo "Log file short name: $log_file"
	echo "Log file in database: $file_name"

	# 2) Check if parsed
	exists=$($MYSQL -u $db_user -p$db_pass -h $db_host -e "SELECT exists( SELECT file_name FROM files WHERE file_name='$file_name')\G;" $db_name | tail -n1 | cut -d":" -f2)

	if [ $exists -eq 1 ]; then
		echo "File $log_file already parsed!"
	else
		echo "Parsing $log_file"
		
		# 3) Gunzip
		echo $GUNZIP $log_file_complete_path
		$GUNZIP $log_file_complete_path
		if [ $? -ne 0 ]; then
			echo "Problems gunzipping file log_file_complete_path. File not parsed. Abort"
			exit 1
		fi

		
		# 4) Parse
		wrong=1
		while read line; do
			OLD_IFS="$IFS"
			IFS=":"
			sgeArr=($line)
			
			job_date=$(date -d @${sgeArr[10]} '+%Y-%m-%d')
			id_job=${sgeArr[5]}-${sgeArr[35]}			
			if [ ${sgeArr[9]} -eq '0' ]; then
			# queued job never start, (starttime=0 lets see if nwe neeed end time=0)  let's log it and do nothing
				echo "$id_job from ${sgeArr[3]} has a start_time of ${sgeArr[8]} which means it never started"
			else
			# add a new entry
			$MYSQL -u $db_user -p$db_pass -h $db_host $db_name -e "INSERT INTO account (id_job, date, qname, hostname, groupname, owner, job_name, job_number, account, priority, submission_time, start_time, end_time, failed, exit_status, ru_wallclock, ru_utime, ru_stime, ru_maxrss, ru_ixrss, ru_ismrss, ru_idrss, ru_isrss, ru_minflt, ru_majflt, ru_nswap, ru_inblock, ru_oublock, ru_msgsnd, ru_msgrcv, ru_nsignals, ru_nvcsw, ru_nivcsw, project, department, granted_pe, slots, task_number, cpu, mem, io, category, iow, pe_taskid, maxvmem, arid, ar_submission_time) VALUES ('$id_job','$job_date', '${sgeArr[0]}', '${sgeArr[1]}', '${sgeArr[2]}', '${sgeArr[3]}', '${sgeArr[4]}', '${sgeArr[5]}', '${sgeArr[6]}', '${sgeArr[7]}', '${sgeArr[8]}', '${sgeArr[9]}', '${sgeArr[10]}', '${sgeArr[11]}', '${sgeArr[12]}', '${sgeArr[13]}', '${sgeArr[14]}', '${sgeArr[15]}', '${sgeArr[16]}', '${sgeArr[17]}', '${sgeArr[18]}', '${sgeArr[19]}', '${sgeArr[20]}', '${sgeArr[21]}', '${sgeArr[22]}', '${sgeArr[23]}', '${sgeArr[24]}', '${sgeArr[25]}', '${sgeArr[26]}', '${sgeArr[27]}', '${sgeArr[28]}', '${sgeArr[29]}', '${sgeArr[30]}', '${sgeArr[31]}', '${sgeArr[32]}', '${sgeArr[33]}', '${sgeArr[34]}', '${sgeArr[35]}', '${sgeArr[36]}', '${sgeArr[37]}', '${sgeArr[38]}', '${sgeArr[39]}', '${sgeArr[40]}', '${sgeArr[41]}', '${sgeArr[42]}', '${sgeArr[43]}', '${sgeArr[44]}')"
				if [ $? -ne '0' ]; then
				# Something went wrong. not updating database
					wrong=0
				fi
			IFS="$OLD_IFS"	
			fi
		done < $sge_logs/$file_name
		echo "File $file_name parsed!"
		
		# 5) gzip again
		echo $GZIP $sge_logs/$file_name
		$GZIP $sge_logs/$file_name || echo "Problems zipping file log_file_complete_path. File parsed. We DO NOT ABORT"

		echo "### ENDING FILE PARSING ###"
		echo ""

		# 6) update databases with parsed file
		if [ $wrong ]; then
			mysql -u $db_user -p$db_pass -h $db_host $db_name -e "INSERT INTO files set file_name='$file_name';"
		fi
	fi
done
