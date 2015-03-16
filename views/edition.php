<?php

/**
 * Select Edition view.
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

$summaries = '';
echo "<h2 style='font-size: 1.8em;'>" . lang("edition_thank_you") . "</h2>";
echo row_open();

foreach ($editions as $id => $edition) {
    $summaries .= "<p class='edition-info edition-help-" . $edition['class'] . " theme-hidden'>" . lang('edition_summary_' . $edition['class']) . "</p>\n";
    echo column_open(4);
    echo box_open($edition['short_name'] . ' ' . lang('edition_edition'), array('id' => 'box-' . $edition['dom_id'], 'class' => 'edition-info-box'));
    echo box_content(
        row_open() .
        column_open(3, NULL, NULL, array('class' => 'theme-center-text')) .
        image($edition['logo']) .
        column_close() .
        column_open(9) .
        lang('edition_exec_summary_' . $edition['class']) .
        column_close() .
        row_close()
    );
    if ($edition['available'])
        $footer = "<div class='edition-label theme-hidden'>" . lang('base_install') . ' ' . $edition['short_name'] . "</div>" . 
            anchor_select('#', 'high', array('id' => $edition['dom_id'], 'class' => 'edition-selector', 'data' => array('conf' => $edition['configlet_file'])));
    else if ($edition['beta'])
        $footer = "<div class='theme-text-alert'>" . lang('edition_not_available_during_testing') . "</div>";
    else
        $footer = "<div class='theme-text-alert'>" . lang('edition_not_available') . "</div>";
    echo box_footer('footer-' . $edition['dom_id'], $footer, array('class' => 'edition-option-footer'));
    echo box_close();
    echo column_close();
}

echo row_close();
echo $summaries;
if ($selected !== FALSE)
    echo "<div id='edition-selected' class='theme-hidden'>" . $selected . "</div>";
echo modal_info("wizard_next_showstopper", lang('base_error'), lang('edition_no_edition_selected'), array('type' => 'warning'));
