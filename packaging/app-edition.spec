
Name: app-edition
Epoch: 1
Version: 1.1.6
Release: 1%{dist}
Summary: Edition Manager
License: Proprietary
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base

%description
The Edition Manager provides a quick way to upgrade from Community to Professional on a new install.

%package core
Summary: Edition Manager - Core
License: Proprietary
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-clearcenter-core

%description core
The Edition Manager provides a quick way to upgrade from Community to Professional on a new install.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/edition
cp -r * %{buildroot}/usr/clearos/apps/edition/


if [ -d %{buildroot}/usr/clearos/apps/edition/libraries_zendguard ]; then
    rm -rf %{buildroot}/usr/clearos/apps/edition/libraries
    mv %{buildroot}/usr/clearos/apps/edition/libraries_zendguard %{buildroot}/usr/clearos/apps/edition/libraries
fi

%post
logger -p local6.notice -t installer 'app-edition - installing'

%post core
logger -p local6.notice -t installer 'app-edition-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/edition/deploy/install ] && /usr/clearos/apps/edition/deploy/install
fi

[ -x /usr/clearos/apps/edition/deploy/upgrade ] && /usr/clearos/apps/edition/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-edition - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-edition-core - uninstalling'
    [ -x /usr/clearos/apps/edition/deploy/uninstall ] && /usr/clearos/apps/edition/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/edition/controllers
/usr/clearos/apps/edition/htdocs
/usr/clearos/apps/edition/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/edition/packaging
%exclude /usr/clearos/apps/edition/tests
%dir /usr/clearos/apps/edition
/usr/clearos/apps/edition/deploy
/usr/clearos/apps/edition/language
/usr/clearos/apps/edition/libraries
