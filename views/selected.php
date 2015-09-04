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
echo box_footer('footer-selected', lang('edition_currently_running') . ': ' . $selected['name'], array('class' => 'edition-option-footer'));
echo box_close();
