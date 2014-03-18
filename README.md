SGE Account
===========

### Description

A Sun Grid Engine accounting system website done with PHP (using Twitter Bootstrap for UI), MySQL (with PHP ActiveRecord as ORM) and a Bash script parser.

### Requirements

- PHP 5.3, PHP-Mysql and PHP-PDO with the MySQL extension enabled
- MySQL
- Apache (httpd) server

### Content

#### Basic install & configure instructions for SGE & sge_account

##### Install
You can use rpm or tarball for installing sge_account.

##### Config

It uses a configuration file located in **/etc/sge_parser/sge_parser.cfg**

```
# DB_Config
db_user=sge
db_pass=sgepasswd
db_host=localhost
db_name=sge_account

# SGE_Config
sge_logs=/usr/share/gridengine/antcluster/common/
```

##### SGE log files
You don't have to configure sge_account in SGE master. You can rotate its logs and send them to a remote server. You need to configure a rsync server and logrotate for *sge*. You can take a look at the following example:

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
15 6 * * * /usr/bin/sge_parse.sh
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
