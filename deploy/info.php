<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'edition';
$app['version'] = '2.4.1';
$app['release'] = '1';
$app['vendor'] = 'ClearCenter';
$app['packager'] = 'ClearCenter';
$app['license'] = 'Proprietary';
$app['license_core'] = 'Proprietary';
$app['description'] = lang('edition_app_description');

/////////////////////////////////////////////////////////////////////////////
// App name and categories
/////////////////////////////////////////////////////////////////////////////

$app['name'] = lang('edition_app_name');
$app['category'] = lang('base_category_system');
$app['subcategory'] = lang('base_subcategory_settings');

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['core_directory_manifest'] = array(
    '/etc/clearos/edition.d' => array()
);

$app['core_file_manifest'] = array( 
    'business-7.conf' => array (
        'target' => '/etc/clearos/edition.d',
        'mode' => '0644',
    ),
    'community-7.conf' => array (
        'target' => '/etc/clearos/edition.d',
        'mode' => '0644',
    ),
    'home-7.conf' => array (
        'target' => '/etc/clearos/edition.d',
        'mode' => '0644',
    ),
);

// KLUDGE: The following dependencies should be moved to a new app at some
// point (e.g. app-clearos). For now, these are here to make sure the packages
// are installed on ClearOS, but not a barebones ClearVM system.
// - syswatch
// - webconfig-php-* (to avoid webconfig restarts)

$app['core_requires'] = array(
    'app-clearcenter-core',
    'app-base-core >= 1:2.4.0',
    'clearos-release >= 7-4.1',
    'syswatch',
    'webconfig-php-gd',
    'webconfig-php-ldap',
    'webconfig-php-mysql'
);
