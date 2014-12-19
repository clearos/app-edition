<?php

/**
 * Upgrade banner view.
 *
 * @category   apps
 * @package    edition
 * @subpackage views
 * @author     ClearCenter <developer@clearcenter.com>
 * @copyright  2012 ClearCenter
 * @license    http://www.clearcenter.com/Company/terms.html ClearSDN license
 * @link       http://www.clearcenter.com/support/documentation/clearos/edition/
 */

///////////////////////////////////////////////////////////////////////////////
// Load dependencies
///////////////////////////////////////////////////////////////////////////////

$this->lang->load('edition');

///////////////////////////////////////////////////////////////////////////////
// Infobox
///////////////////////////////////////////////////////////////////////////////

if ($professional_already_installed) {
    echo infobox_highlight('Thank You', 'blah', array('id' => 'professional_already_installed'));
    return;
}

///////////////////////////////////////////////////////////////////////////////
// Content
///////////////////////////////////////////////////////////////////////////////

// TODO: translate

echo "<h2 style='font-size: 1.8em;'>Thank You for Choosing ClearOS</h2>";
echo row_open();
echo column_open(6);
echo "<p class='edition-info'>ClearOS Community Edition is supported by a community of volunteers donating their time and knowledge via the ClearFoundation.  This support model may not be the best solution for your business or organisation.  Please consider all factors (technical skill, number of users, data sensitivity, resources etc.) when considering using ClearOS Community Edition in a commercial, professional, educational or large-scale deployment.";
echo "<p class='edition-info'>ClearCenter offers a commercially supported version of ClearOS called ClearOS Professional Edition.</p>";
echo infobox_warning('Professional Evaluations', "If you would like to upgrade to a 30-day evaluation of ClearOS Professional Edition, please select ClearOS Professional Upgrade.");
echo column_close();
echo column_open(6);

echo row_open();

$community_footer = "<div class='edition-label theme-hidden'>Install Community</div>" . anchor_select('#', 'high', array('id' => 'select-community'));
// FIXME: re-enable, or better yet, make this a configurable option for betas
// $pro_footer = "<div class='edition-label theme-hidden'>Install Professional</div>" . anchor_select('#', 'high', array('id' => 'select-professional'));
$pro_footer = "Coming Soon";
echo column_open(6);
echo box_open('Community Edition');
echo box_content(image('community_logo.png', array('size' => "150x150")),  array('class' => 'theme-center-text'));
echo box_footer('footer-community', $community_footer, array('class' => 'edition-option-footer'));
echo box_close();
echo column_close();

echo column_open(6);
echo box_open('Professional Edition');
echo box_content(image('professional_logo.png', array('size' => "150x150")),  array('class' => 'theme-center-text'));
echo box_footer('footer-pro', $pro_footer, array('class' => 'edition-option-footer'));
echo box_close();
echo column_close();

echo row_close();

echo column_close();
echo row_close();
echo modal_info("wizard_next_showstopper", lang('base_error'), lang('edition_no_edition_selected'), array('type' => 'warning'));
