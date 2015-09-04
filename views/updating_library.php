<?php

/**
 * Edition updating libary in progress view.
 *
 * @category   apps
 * @package    edition
 * @subpackage views
 * @author     ClearCenter <developer@clearcenter.com>
 * @copyright  2011 ClearCenter
 * @license    http://www.clearcenter.com/Company/terms.html ClearSDN license
 * @link       http://www.clearcenter.com/support/documentation/clearos/edition/
 */

///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('base');
$this->lang->load('edition');

///////////////////////////////////////////////////////////////////////////////
// Infobox
///////////////////////////////////////////////////////////////////////////////

$options['buttons']  = array(
    anchor_custom('/app/edition/abort_update', lang('edition_abort_update'), 'high')
);
echo infobox_info(
    lang('edition_updating'),
    loading('normal', lang('base_loading_software_update')),
    $options
);

