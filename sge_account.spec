#
# spec file for package sge_account
#
# This file and all modifications and additions to the pristine
# package are under the same license as the package itself.
#
# please send bugfixes or comments to jcasals at gmail.com || arnaubria at pic.es

Name:		sge_account
Version:	1.9.0
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
Requires: 	perl(Config::Simple) perl(DBI) perl(Data::Dumper) perl(File::Basename) perl(Getopt::Long) perl(Log::Dispatch) perl(Log::Dispatch::File) perl(POSIX) perl(Sys::Hostname) 

Provides:	sge_account


%description
Sge_account is a accounting tool for SGE. It provides regular accounting, billing accounting (accounting divided by queue type) and efficiency accounting.

%prep
%setup -q -n %{name}-%{version}-%{release}

%install
rm -rf %{buildroot}
echo %{buildroot}
mkdir -p %{buildroot}{%{_sysconfdir},/usr,/usr/share,/usr/share/sge,/usr/share/sge/{js,js/raphaeljs,img,images/css,css/images,activerecord,activerecord/models,activerecord/lib,activerecord/lib/adapters,addons,addons/footer_addons,addons/head_addons,addons/index_addons,addons/img},/usr/bin,/usr/sbin,/etc,/etc/sge_parser,/etc/httpd,/etc/httpd/conf.d,/etc/logrotate.d,/var,/var/log,/var/log/sge_accounting}

cp ./usr/sbin/sge_parse_install.pl %{buildroot}/usr/sbin/sge_parse_install.pl
cp ./usr/bin/sge_account_file_parser.pl %{buildroot}/usr/bin/sge_account_file_parser.pl
cp -R ./usr/share/sge/* %{buildroot}/usr/share/sge/
cp ./CONSIDERATIONS.md %{buildroot}/usr/share/sge/CONSIDERATIONS.md
cp ./README.md %{buildroot}/usr/share/sge/README.md
cp ./ADDONS.md %{buildroot}/usr/share/sge/ADDONS.md
cp ./etc/logrotate.d/sge %{buildroot}/etc/logrotate.d/sge
cp ./etc/logrotate.d/sge_parser %{buildroot}/etc/logrotate.d/sge_parser
cp ./etc/httpd/conf.d/accounting.conf %{buildroot}/etc/httpd/conf.d/accounting.conf
cp ./etc/sge_parser.cfg %{buildroot}/etc/sge_parser/sge_parser.cfg

%post
echo "Please, visit https://github.com/jcasals/sge_account for configuration instructions"

%files
%doc   /usr/share/sge/ADDONS.md
%doc   /usr/share/sge/CONSIDERATIONS.md
%doc   /usr/share/sge/README.md
%config /etc/sge_parser/sge_parser.cfg
%config /etc/httpd/conf.d/accounting.conf
%config /etc/logrotate.d/sge
%config /etc/logrotate.d/sge_parser
%attr (0755, sgeaccounting,root) /var/log/sge_accounting/
%attr (-,sgeaccounting,sgeaccounting) /usr/share/sge/
%attr(0750, root,root) /usr/sbin/sge_parse_install.pl
%attr(0755, sgeaccounting,root) /usr/bin/sge_account_file_parser.pl


%clean
rm -rf %{buildroot}

%changelog
* Wed Jun 4 2014 Arnau Bria <arnau.bria@gmail.com> 1.9.0-1
We have rewrite 50% of the code. There are important changes that involve MySQL and the parser itself. Also, we found some bugs.
So, it would be nice to have a upgrade,but we do not have time to provide it, so we recommend a fresh install. Easy one :-)
- New parser: per (the time for uploading entries into DDBB has been reduced by 10), real log ...
- New DDBB and table schema (added some fields for calculating efficiency)
- Job Efficiency plots (requested resources (h_rt, h_vmem) / used reources)
- All SGE stuff (General Accoutning, Billing and Efficiency are now part of the main sge_Accountand not an addon anymore).
 
* Wed Nov 26 2013 Arnau Bria <arnau.bria@gmail.com> 1.2.0-1
- Big Jump :-)
- Addons function
- Plot per group
* Wed Apr 24 2013 Arnau Bria <arnau.bria@gmail.com> 0.0.0-1
- First release
