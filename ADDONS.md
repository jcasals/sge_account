We call addons to php extra-code.
With this addons you can add header/footer or extra accounting.
As example, in our Lab we provide Isilon and DDN accounting and complete hub to all our sites (Ganglia/Icinga/Wiki....)

Take a look at our Screenshot section.


We have 4 diff addons:

$sge_account_root/addons/head_addons
====================================
In main header.php we include all files located under this path.

Thy look like:

<li class="dropdown">
	<a class="dropdown-toggle" data-toggle="dropdown">Documentation <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><a href="http://wiki.linux.crg.es">Internal SIT documentation</a></li>
		<li><a href="http://www.linux.crg.es">User documentation</a></li>
	</ul>
</li>   

$sge_account_root/addons/footer_addons
======================================
In main footer.php we include all files located under this path.

We add our logo:

<?php
	$path=(dirname(__FILE__));
	echo '<a target=_blank href="http://www.crg.cat"><img width="175px" src="$path/../img/crg.png"/></a>';
?>


$sge_account_root/addons/index_addons
=====================================
In main index.php we include all files located under this path.

<h4>SGE Billing (Detailed)</h4>
<a href="addons/sge_billing/sge.php">SGE Billing</a> <br><br>


$sge_account_root/addons/$extra_account
=======================================

Taking the above index addon example, we add a extra sge_billing . We must create some links:

addons/sge_billing/
total 28
	activerecord -> ../../activerecord/
	css -> ../../css
	img -> ../../img
	js -> ../../js
	sge_db.php
	sge.php
	sge_set.php

*php refers to extra accounting code.

