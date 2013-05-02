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

1. Download the ZIP package and put the content in your html directory (i.e.: <i>/var/www/html</i>)
2. Go to file <b>setup/config.ini</b> and specify your database parameters in the <i>DB_Config</i> section. If you are running this in localhost, you just have to specify your <i>db_user</i> and <i>db_pass</i> parameters. Remember to have this user created with permissions to create databases.
3. Check your sge logs location and specify it in the <b>setup/config.ini</b> file in the <i>SGE_Config</i> section. This is important for the logs parser to be able to read it and insert the data in the database.
4. If you are sure you put the correct values, run the <b>setup/setup.sh</b> script and the database will be created.
5. Remember that you must add the parser <b>scripts/parser.sh</b> to your preferred cron system at <b>00:01AM</b> to have a good insertion of the data in the database.

Content
=======

TODO

Tools used
==========

- Twitter Bootstrap (CSS, JS and HTML)
- jQuery and jQuery UI
- Datatables (Bootstrap implementation)
- Raphael JS (raphael.js, g.raphael.js, g.pie.js)

Copyright and Licensing
=======================


Important
=========

This is a beta version. Things may not work as expected or as you think it should work. For any doubt, advice or complaint, please tell me and I will try to fix it as soon as possible.
