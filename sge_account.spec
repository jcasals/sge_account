#
# spec file for package sge_account
#
# This file and all modifications and additions to the pristine
# package are under the same license as the package itself.
#
# please send bugfixes or comments to jcasals at gmail.com || arnaubria at pic.es

Name:		sge_account
Version:	1.2.0
Release:	1
Summary:	SGE accounting tool
Group:		Administration Tools
License:	GPL
URL:		https://github.com/jcasals/sge_account
Source0:	%{name}-%{version}-%{release}.tar.gz
BuildRoot:	%{_tmppath}/%{name}-%{version}-%{release}-root-%(%{__id_u} -n)

Requires:	php
Requires:	webserver
Requires:	php

Provides:	sge_account


%description
Sge_account is a accounting tool for SGE.

%prep
%setup -q -n %{name}-%{version}-%{release}

%install
rm -rf %{buildroot}
echo %{buildroot}
mkdir -p %{buildroot}{%{_sysconfdir},/usr,/usr/share,/usr/share/sge,/usr/share/sge/{js,js/raphaeljs,img,images/css,css/images,activerecord,activerecord/models,activerecord/lib,activerecord/lib/adapters,addons,addons/footer_addons,addons/head_addons,addons/index_addons,addons/img},/usr/bin,/usr/sbin,/etc,/etc/sge_parser,/etc/httpd,/etc/httpd/conf.d,/etc/logrotate.d}


cp ./usr/sbin/sge_parse_install.sh %{buildroot}/usr/sbin/sge_parse_install.sh
cp ./usr/bin/sge_parse.sh %{buildroot}/usr/bin/sge_parse.sh
cp ./usr/share/sge/head.php %{buildroot}/usr/share/sge/head.php
cp ./usr/share/sge/js/bootstrap.datatables.js %{buildroot}/usr/share/sge/js/bootstrap.datatables.js
cp ./usr/share/sge/js/jquery.js %{buildroot}/usr/share/sge/js/jquery.js
cp ./usr/share/sge/js/jquery-ui.js %{buildroot}/usr/share/sge/js/jquery-ui.js
cp ./usr/share/sge/js/jquery.validate.min.js %{buildroot}/usr/share/sge/js/jquery.validate.min.js
cp ./usr/share/sge/js/bootstrap.min.js %{buildroot}/usr/share/sge/js/bootstrap.min.js
cp ./usr/share/sge/js/.DS_Store %{buildroot}/usr/share/sge/js/.DS_Store
cp ./usr/share/sge/js/plot.js %{buildroot}/usr/share/sge/js/plot.js
cp ./usr/share/sge/js/jquery.dataTables.min.js %{buildroot}/usr/share/sge/js/jquery.dataTables.min.js
cp ./usr/share/sge/js/bootstrap.js %{buildroot}/usr/share/sge/js/bootstrap.js
cp ./usr/share/sge/js/raphaeljs/raphael.js %{buildroot}/usr/share/sge/js/raphaeljs/raphael.js
cp ./usr/share/sge/js/raphaeljs/g.raphael.js %{buildroot}/usr/share/sge/js/raphaeljs/g.raphael.js
cp ./usr/share/sge/js/raphaeljs/g.pie.js %{buildroot}/usr/share/sge/js/raphaeljs/g.pie.js
cp ./usr/share/sge/js/js.js %{buildroot}/usr/share/sge/js/js.js
cp ./usr/share/sge/sge_db.php %{buildroot}/usr/share/sge/sge_db.php
cp ./usr/share/sge/format_bytes.php %{buildroot}/usr/share/sge/format_bytes.php
cp ./usr/share/sge/sge_set.php %{buildroot}/usr/share/sge/sge_set.php
cp ./usr/share/sge/images/favicon.ico %{buildroot}/usr/share/sge/images/favicon.ico
cp ./usr/share/sge/header.php %{buildroot}/usr/share/sge/header.php
cp ./usr/share/sge/index.php %{buildroot}/usr/share/sge/index.php
cp ./usr/share/sge/activerecord/models/Account.php %{buildroot}/usr/share/sge/activerecord/models/Account.php
cp ./usr/share/sge/activerecord/models/Isilon.php %{buildroot}/usr/share/sge/activerecord/models/Isilon.php
cp ./usr/share/sge/activerecord/models/Ddn.php %{buildroot}/usr/share/sge/activerecord/models/Ddn.php
cp ./usr/share/sge/activerecord/lib/Model.php %{buildroot}/usr/share/sge/activerecord/lib/Model.php
cp ./usr/share/sge/activerecord/lib/Expressions.php %{buildroot}/usr/share/sge/activerecord/lib/Expressions.php
cp ./usr/share/sge/activerecord/lib/Column.php %{buildroot}/usr/share/sge/activerecord/lib/Column.php
cp ./usr/share/sge/activerecord/lib/Reflections.php %{buildroot}/usr/share/sge/activerecord/lib/Reflections.php
cp ./usr/share/sge/activerecord/lib/Relationship.php %{buildroot}/usr/share/sge/activerecord/lib/Relationship.php
cp ./usr/share/sge/activerecord/lib/SQLBuilder.php %{buildroot}/usr/share/sge/activerecord/lib/SQLBuilder.php
cp ./usr/share/sge/activerecord/lib/Singleton.php %{buildroot}/usr/share/sge/activerecord/lib/Singleton.php
cp ./usr/share/sge/activerecord/lib/Inflector.php %{buildroot}/usr/share/sge/activerecord/lib/Inflector.php
cp ./usr/share/sge/activerecord/lib/DateTime.php %{buildroot}/usr/share/sge/activerecord/lib/DateTime.php
cp ./usr/share/sge/activerecord/lib/Connection.php %{buildroot}/usr/share/sge/activerecord/lib/Connection.php
cp ./usr/share/sge/activerecord/lib/ConnectionManager.php %{buildroot}/usr/share/sge/activerecord/lib/ConnectionManager.php
cp ./usr/share/sge/activerecord/lib/Exceptions.php %{buildroot}/usr/share/sge/activerecord/lib/Exceptions.php
cp ./usr/share/sge/activerecord/lib/Validations.php %{buildroot}/usr/share/sge/activerecord/lib/Validations.php
cp ./usr/share/sge/activerecord/lib/adapters/SqliteAdapter.php %{buildroot}/usr/share/sge/activerecord/lib/adapters/SqliteAdapter.php
cp ./usr/share/sge/activerecord/lib/adapters/MysqlAdapter.php %{buildroot}/usr/share/sge/activerecord/lib/adapters/MysqlAdapter.php
cp ./usr/share/sge/activerecord/lib/adapters/OciAdapter.php %{buildroot}/usr/share/sge/activerecord/lib/adapters/OciAdapter.php
cp ./usr/share/sge/activerecord/lib/adapters/PgsqlAdapter.php %{buildroot}/usr/share/sge/activerecord/lib/adapters/PgsqlAdapter.php
cp ./usr/share/sge/activerecord/lib/CallBack.php %{buildroot}/usr/share/sge/activerecord/lib/CallBack.php
cp ./usr/share/sge/activerecord/lib/Config.php %{buildroot}/usr/share/sge/activerecord/lib/Config.php
cp ./usr/share/sge/activerecord/lib/Utils.php %{buildroot}/usr/share/sge/activerecord/lib/Utils.php
cp ./usr/share/sge/activerecord/lib/Table.php %{buildroot}/usr/share/sge/activerecord/lib/Table.php
cp ./usr/share/sge/activerecord/lib/Serialization.php %{buildroot}/usr/share/sge/activerecord/lib/Serialization.php
cp ./usr/share/sge/activerecord/ActiveRecord.php %{buildroot}/usr/share/sge/activerecord/ActiveRecord.php
cp ./usr/share/sge/activerecord/README.md %{buildroot}/usr/share/sge/activerecord/README.md
cp ./usr/share/sge/activerecord/CHANGELOG %{buildroot}/usr/share/sge/activerecord/CHANGELOG
cp ./usr/share/sge/activerecord/LICENSE %{buildroot}/usr/share/sge/activerecord/LICENSE
cp ./usr/share/sge/img/gelogo.png %{buildroot}/usr/share/sge/img/gelogo.png
cp ./usr/share/sge/img/glyphicons-halflings.png %{buildroot}/usr/share/sge/img/glyphicons-halflings.png
cp ./usr/share/sge/img/favicon.ico %{buildroot}/usr/share/sge/img/favicon.ico
cp ./usr/share/sge/img/glyphicons-halflings-white.png %{buildroot}/usr/share/sge/img/glyphicons-halflings-white.png
cp ./usr/share/sge/img/gelogo2.png %{buildroot}/usr/share/sge/img/gelogo2.png
cp ./usr/share/sge/img/crg.png %{buildroot}/usr/share/sge/img/crg.png
cp ./usr/share/sge/sge.php %{buildroot}/usr/share/sge/sge.php
cp ./usr/share/sge/css/bootstrap.min.css %{buildroot}/usr/share/sge/css/bootstrap.min.css
cp ./usr/share/sge/css/jquery-ui.css %{buildroot}/usr/share/sge/css/jquery-ui.css
cp ./usr/share/sge/css/images/sort_desc_disabled.png %{buildroot}/usr/share/sge/css/images/sort_desc_disabled.png
cp ./usr/share/sge/css/images/ui-bg_flat_75_ffffff_40x100.png %{buildroot}/usr/share/sge/css/images/ui-bg_flat_75_ffffff_40x100.png
cp ./usr/share/sge/css/images/sort_desc.png %{buildroot}/usr/share/sge/css/images/sort_desc.png
cp ./usr/share/sge/css/images/ui-bg_glass_65_ffffff_1x400.png %{buildroot}/usr/share/sge/css/images/ui-bg_glass_65_ffffff_1x400.png
cp ./usr/share/sge/css/images/back_disabled.png %{buildroot}/usr/share/sge/css/images/back_disabled.png
cp ./usr/share/sge/css/images/favicon.ico %{buildroot}/usr/share/sge/css/images/favicon.ico
cp ./usr/share/sge/css/images/ui-bg_glass_75_e6e6e6_1x400.png %{buildroot}/usr/share/sge/css/images/ui-bg_glass_75_e6e6e6_1x400.png
cp ./usr/share/sge/css/images/sort_asc.png %{buildroot}/usr/share/sge/css/images/sort_asc.png
cp ./usr/share/sge/css/images/back_enabled.png %{buildroot}/usr/share/sge/css/images/back_enabled.png
cp ./usr/share/sge/css/images/ui-icons_2e83ff_256x240.png %{buildroot}/usr/share/sge/css/images/ui-icons_2e83ff_256x240.png
cp "./usr/share/sge/css/images/Sorting icons.psd" "%{buildroot}/usr/share/sge/css/images/Sorting icons.psd"
cp ./usr/share/sge/css/images/forward_enabled_hover.png %{buildroot}/usr/share/sge/css/images/forward_enabled_hover.png
cp ./usr/share/sge/css/images/ui-bg_highlight-soft_75_cccccc_1x100.png %{buildroot}/usr/share/sge/css/images/ui-bg_highlight-soft_75_cccccc_1x100.png
cp ./usr/share/sge/css/images/forward_disabled.png %{buildroot}/usr/share/sge/css/images/forward_disabled.png
cp ./usr/share/sge/css/images/ui-icons_cd0a0a_256x240.png %{buildroot}/usr/share/sge/css/images/ui-icons_cd0a0a_256x240.png
cp ./usr/share/sge/css/images/ui-bg_glass_55_fbf9ee_1x400.png %{buildroot}/usr/share/sge/css/images/ui-bg_glass_55_fbf9ee_1x400.png
cp ./usr/share/sge/css/images/ui-icons_888888_256x240.png %{buildroot}/usr/share/sge/css/images/ui-icons_888888_256x240.png
cp ./usr/share/sge/css/images/ui-icons_454545_256x240.png %{buildroot}/usr/share/sge/css/images/ui-icons_454545_256x240.png
cp ./usr/share/sge/css/images/ui-bg_glass_75_dadada_1x400.png %{buildroot}/usr/share/sge/css/images/ui-bg_glass_75_dadada_1x400.png
cp ./usr/share/sge/css/images/ui-icons_f6cf3b_256x240.png %{buildroot}/usr/share/sge/css/images/ui-icons_f6cf3b_256x240.png
cp ./usr/share/sge/css/images/ui-bg_glass_95_fef1ec_1x400.png %{buildroot}/usr/share/sge/css/images/ui-bg_glass_95_fef1ec_1x400.png
cp ./usr/share/sge/css/images/forward_enabled.png %{buildroot}/usr/share/sge/css/images/forward_enabled.png
cp ./usr/share/sge/css/images/ui-icons_222222_256x240.png %{buildroot}/usr/share/sge/css/images/ui-icons_222222_256x240.png
cp ./usr/share/sge/css/images/ui-bg_inset-soft_95_fef1ec_1x100.png %{buildroot}/usr/share/sge/css/images/ui-bg_inset-soft_95_fef1ec_1x100.png
cp ./usr/share/sge/css/images/ui-bg_glass_75_ffffff_1x400.png %{buildroot}/usr/share/sge/css/images/ui-bg_glass_75_ffffff_1x400.png
cp ./usr/share/sge/css/images/ui-bg_flat_0_aaaaaa_40x100.png %{buildroot}/usr/share/sge/css/images/ui-bg_flat_0_aaaaaa_40x100.png
cp ./usr/share/sge/css/images/sort_both.png %{buildroot}/usr/share/sge/css/images/sort_both.png
cp ./usr/share/sge/css/images/sort_asc_disabled.png %{buildroot}/usr/share/sge/css/images/sort_asc_disabled.png
cp ./usr/share/sge/css/images/back_enabled_hover.png %{buildroot}/usr/share/sge/css/images/back_enabled_hover.png
cp ./usr/share/sge/css/bootstrap.css %{buildroot}/usr/share/sge/css/bootstrap.css
cp ./usr/share/sge/css/css.css %{buildroot}/usr/share/sge/css/css.css
cp ./usr/share/sge/css/bootstrap.datatables.css %{buildroot}/usr/share/sge/css/bootstrap.datatables.css
cp ./usr/share/sge/footer.php %{buildroot}/usr/share/sge/footer.php
cp ./README.md %{buildroot}/README.md
cp ./etc/logrotate.d/sge %{buildroot}/etc/logrotate.d/sge
cp ./etc/httpd/conf.d/accounting.conf %{buildroot}/etc/httpd/conf.d/accounting.conf
cp ./etc/sge_parser.cfg %{buildroot}/etc/sge_parser.cfg
cp ./ADDONS.md %{buildroot}/ADDONS.md

%post
echo "You must run /usr/bin/sge_parse_install.sh"


%files
%attr(0750, root,root) /usr/sbin/sge_parse_install.sh
%attr(0755, root,root) /usr/bin/sge_parse.sh
%defattr(-,root,root,-)
	/usr/share/sge/head.php
	/usr/share/sge/js
	/usr/share/sge/sge.php
	/usr/share/sge/sge_db.php
	/usr/share/sge/format_bytes.php
	/usr/share/sge/sge_set.php
	/usr/share/sge/images
	/usr/share/sge/header.php
	/usr/share/sge/index.php
	/usr/share/sge/activerecord
	/usr/share/sge/img
	/usr/share/sge/css
	/usr/share/sge/addons
	/usr/share/sge/footer.php
	/README.md
	/etc/sge_parser
	/etc/logrotate.d/sge
	/etc/httpd/conf.d/accounting.conf
	/etc/sge_parser.cfg
	/ADDONS.md



%clean
rm -rf %{buildroot}

%changelog
* Wed Nov 26 2013 Arnau Bria <arnau.bria@gmail.com> 1.2.0-1
- Big Jump :-)
- Addons function
- Plot per group
* Wed Apr 24 2013 Arnau Bria <arnau.bria@gmail.com> 0.0.0-1
- First release
