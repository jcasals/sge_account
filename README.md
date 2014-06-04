SGE Account
===========

### Description

A Sun Grid Engine accounting system website done with PHP (using Twitter Bootstrap for UI), MySQL (with PHP ActiveRecord as ORM) and a Perlscript parser.

### Version 2 

We have rewrite 50% of the code. There are important changes that involve MySQL and the parser itself. Also, we found some bugs.
So, it would be nice to have a upgrade,but we do not have time to provide it, so we recommend a fresh install. Easy one :-)

- New parser: per (the time for uploading entries into DDBB has been reduced by 10), real log ...
- New DDBB and table schema (added some fields for calculating efficiency)
- Job Efficiency plots (requested resources (h_rt, h_vmem) / used reources)
- All SGE stuff (General Accoutning, Billing and Efficiency are now part of the main sge_Accountand not an addon anymore).

### Requirements

- PHP 5.3, PHP-Mysql and PHP-PDO with the MySQL extension enabled
- MySQL
- Apache (httpd) server
- Few Perl modules (perl-Config-Simple.noarch perl-Log-Dispatch Getopt::Long)

### Basic install 

You can use rpm or tarball for installing sge_account.
You don't have to compile as it's php and  perl code.

#### Config

It uses a configuration file located in **/etc/sge_parser/sge_parser.cfg**

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

Use your own calues for db_* and sge_logs should point to you accounting log PATH.

#### SGE log files

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
