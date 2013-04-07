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
2. Go to file <b>setup/config.ini</b> and specify your database parameters in the <i>DB_Config<i> section. If you are running this in localhost, you just have to specify your <i>db_user</i> and <i>db_pass</i> parameters. Remember to have this user created with permissions to create databases.
3. Check where do you have your sge logs directory and specify it in the <b>setup/config.ini</b> file in the <i>SGE_Config</i> section. This is important for the logs parser to be able to read it and insert the data in the database.
4. Remember that you must add the parser <b>scripts/parser.sh</b> to your preferred cron system at <b>00:01AM</b> to have a good insertion of the data in the database.

Content
=======

- <b>activerecord</b>: PHP ActiveRecord files. This is an ORM for PHP.
- <b>css</b>: All stylesheet files. the file <i>css.css</i> is our own stylesheet file.
- <b>images</b>: Various images of the project.
- <b>img</b>
- <b>js</b>
- <b>scripts</b>
- <b>setup</b>
- <b>db.php</b>
- <b>footer.php</b>
- <b>group.php</b>
- <b>head.php</b>
- <b>header.php</b>
- <b>index.php</b>
