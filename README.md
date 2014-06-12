SGE Account
===========

# Description

A Sun Grid Engine accounting system website done with PHP (using Twitter Bootstrap for UI), MySQL (with PHP ActiveRecord as ORM) and a Perlscript parser.

## Version 2 

We have rewrite 50% of the code. There are important changes that involve MySQL and the parser itself. Also, we found some bugs.
So, it would be nice to have a upgrade,but we do not have time to provide it, so we recommend a fresh install. Easy one :-)

- New parser: per (the time for uploading entries into DDBB has been reduced by 10), real log ...
- New DDBB and table schema (added some fields for calculating efficiency)
- Job Efficiency plots (requested resources (h_rt, h_vmem) / used reources)
- All SGE stuff (General Accoutning, Billing and Efficiency are now part of the main sge_Accountand not an addon anymore).

## Requirements

- PHP 5.3, PHP-Mysql and PHP-PDO with the MySQL extension enabled
- MySQL
- Apache (httpd) server
- Few Perl modules (perl-Config-Simple.noarch perl-Log-Dispatch Getopt::Long)

# Install 

You can use rpm or tarball for installing sge_account.
You should not use root for running any part of this code. We use a UNIX account called sgeaccounting. All the code exept /usr/sbin/sge_parse_install.pl must belong to that user.

## RPM

yum install sge_account-$version-$release.x86_64.rpm

## From source (git)

As sgeaccounting (or any other non-root user) run:

```
git clone https://github.com/jcasals/sge_account.git 
cp -aR sge_account/usr/share/sge /usr/share/sge
cp etc/httpd/conf.d/accounting.conf /etc/httpd/conf.d/accounting.conf
cp etc/logrotate.d/sge /etc/logrotate.d/sge
cp etc/sge_parser.cfg /etc/sge_parser/sge_parser.cfg
cp usr/bin/sge_account_file_parser.pl /usr/bin/sge_account_file_parser.pl
cp usr/sbin/sge_parse_install.pl /usr/sbin/sge_parse_install.pl
chmod 600 /etc/sge_parser/sge_parser.cfg
mkdir /var/log/sge_accounting/
chown -R sgeaccounting.sgeaccounting /var/log/sge_accounting/
```

/usr/share/sge is our http Documentroot. sgeaccounting must have write access to that directory. Feel free to use any other PATH, just change /etc/httpd/conf.d/accounting.conf

# Config

## Config file

All the code scripts use  a configuration file located in **/etc/sge_parser/sge_parser.cfg**

```
[DB_Config]
db_user=dbuser
db_pass=dbpasswd
db_host=localhost
db_name=sge_full_accounting

[SGE_Log]
sge_logs=/usr/share/gridengine/$clustername/common/

[Log]
level=info
```

Use your own values for db_* and sge_logs should point to you accounting log PATH.

## MySQL

We provide a perl script that creates the DDBB,table and user for you. Configure /etc/sge_parser/sge_parser.cfg (see above) and run

```
/usr/sbin/sge_parse_install.pl
```

## Parser

The parser should run in a daily cron. Feel free to configure it in your crontab or /etc/cron.daily ...

```
crontal -b
15 6 * * * /usr/bin/sge_account_file_parser.pl
```
## Logrotate

The SGE master node and only master node must rotate logs using the file /etc/logrotate.d/sge. Edit the file, change defualt values and use yours.

### SGE log files

You don't have to configure sge_account in SGE master. You can rotate its logs and send them to a remote server. You need to configure a rsync server and logrotate for *sge*. You can take a look at the following example:

**If your SGE_ACCOUNT is not your master node, ensure that it's not also running sge logrotate. If so, you're rotating accoutning files twice.**

***rsync***
```
$ cat /etc/rsyncd.conf 

pid file = /var/run/rsyncd.pid
max connections = 5
log file = /var/log/rsync.log	
use chroot = yes
[sge_account]
    read only= no
    path = /usr/share/gridengine/cluster1/common/
    comment = accounting
    uid = sgeadmin
    gid = games
```

***sge logrotate***
```
$ cat /etc/logrotate.d/sge

/usr/share/gridengine/cluster1/common/accounting {
    compress
    create 644 sgeadmin sgeadmin 
    daily
    dateext
    rotate 356
    prerotate
    /usr/bin/rsync -va /usr/share/gridengine/cluster1/common/ $rsync_server::sge_account
    endscript
}
```
\* ***NOTE:*** *dateext* is mandatory because the log parsers looks for file *accounting-$DATE.gz*

Then you must run a daily cron that parses log files:
```
# sge_account_update
15 6 * * * /usr/bin/sge_account_file_parser.pl
```

For further instructions please contact us at jcasals at gmail.com // arnau.bria at gmail.com

### Tools used

- [Twitter Bootstrap](http://getbootstrap.com) 2.2.1 (CSS, JS and HTML)
- [jQuery](http://jquery.com) and [jQuery UI](http://jqueryui.com)
- [Datatables](http://datatables.net/blog/Twitter_Bootstrap) (Bootstrap implementation)
- [Raphael JS](http://raphaeljs.com) (raphael.js, g.raphael.js, g.pie.js)

### Licensing

Our code is released under GPL v3.0.
Twitter Bootstrap is licensed under the Apache License v2.0 (http://www.anidocs.es/bootstrap/docs/)
PHP ActiveRecord is licensed under the MIT License (http://www.phpactiverecord.org/)

### Important

This is a beta version. Things may not work as expected or as you think it should work. For any doubt, advice or complaint, please tell us and we will try to fix it as soon as possible.
