<?php

/**
 * Selected edition view.
 *
 * @category   apps
 * @package    edition
 * @subpackage views
 * @author     ClearCenter <developer@clearcenter.com>
 * @copyright  2015 ClearCenter
 * @license    http://www.clearcenter.com/Company/terms.html ClearSDN license
 * @link       http://www.clearcenter.com/support/documentation/clearos/edition/
 */

///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('edition');

///////////////////////////////////////////////////////////////////////////////
// Content
///////////////////////////////////////////////////////////////////////////////


echo box_open($selected['name'] . ' ' . lang('edition_edition') . ' ' . $selected['base_version']);
echo box_content(
    row_open() .
    column_open(3, NULL, NULL, array('class' => 'theme-center-text')) .
    image($selected['logo']) .
    column_close() .
    column_open(9) .
    lang('edition_exec_summary_' . $selected['class']) .
    column_close() .
    row_close()
);
if ($display_reset)
    echo box_footer('edition_select', anchor_custom('/app/edition/reset', lang('edition_upgrade_edition'), 'low'), array('class' => 'theme-center-text'));
echo box_close();
