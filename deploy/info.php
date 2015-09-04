<?php

/////////////////////////////////////////////////////////////////////////////
// General information
/////////////////////////////////////////////////////////////////////////////

$app['basename'] = 'edition';
$app['version'] = '2.1.10';
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
$app['menu_enabled'] = FALSE;

/////////////////////////////////////////////////////////////////////////////
// Packaging
/////////////////////////////////////////////////////////////////////////////

$app['core_directory_manifest'] = array(
    '/etc/clearos/edition.d' => array()
);

$app['core_file_manifest'] = array( 
    'business-7.1.conf' => array ( 
        'target' => '/etc/clearos/edition.d',
        'mode' => '0644',
    ),
    'community-7.1.conf' => array ( 
        'target' => '/etc/clearos/edition.d',
        'mode' => '0644',
    ),
    'home-7.1.conf' => array ( 
        'target' => '/etc/clearos/edition.d',
        'mode' => '0644',
    ),
);
$app['core_requires'] = array(
    'app-clearcenter-core',
);
