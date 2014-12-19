<?php

/** * Edition javascript helper.
 *
 * @category   apps
 * @package    edition
 * @subpackage javascript
 * @author     ClearFoundation <developer@clearfoundation.com>
 * @copyright  2012 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/edition/
 */

///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.  
//
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// B O O T S T R A P
///////////////////////////////////////////////////////////////////////////////

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

///////////////////////////////////////////////////////////////////////////////
// T R A N S L A T I O N S
///////////////////////////////////////////////////////////////////////////////

clearos_load_language('edition');

///////////////////////////////////////////////////////////////////////////////
// J A V A S C R I P T
///////////////////////////////////////////////////////////////////////////////

header('Content-Type:application/x-javascript');
?>

var lang_upgrade = '<?php echo lang('edition_upgrade'); ?>';
var lang_downgrade = '<?php echo lang('edition_downgrade'); ?>';
var edition = 'community';

$(document).ready(function() {


    $("#select-professional").on('click', function(e) {
        e.preventDefault();
        $("#footer-pro div.edition-label").show();
        $("#select-professional").hide();
        $("#select-professional").html(lang_upgrade);
        $("#select-community").html(lang_downgrade);
        $("#select-community").show();
        $("#footer-community div.edition-label").hide();
        edition = 'professional';
    });
    $("#select-community").on('click', function(e) {
        e.preventDefault();
        $("#footer-pro div.edition-label").hide();
        $("#select-professional").show();
        $("#select-professional").html(lang_upgrade);
        $("#select-community").html(lang_downgrade);
        $("#select-community").hide();
        $("#footer-community div.edition-label").show();
        edition = 'community';
    });

    // Wizard previous/next button handling
    //-------------------------------------

    $('#wizard_nav_next').on('click', function(e) {
        // if ($("#select-community").is(':visible') && $("#select-professional").is(':visible')) {
        // FIXME: no Professional edition yet
        if ($("#select-community").is(':visible')) {
            e.preventDefault();
            clearos_modal_infobox_open('wizard_next_showstopper');
            return;
        }
        update_edition(edition);
    });

});

/* Update edition */

function update_edition(edition) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/app/edition/update_edition',
        data: 'ci_csrf_token=' + $.cookie('ci_csrf_token') + '&edition=' + edition,
        success: function(data) {
        },
        error: function(xhr, text, err) {
        }
    });
}

// vim: ts=4 syntax=javascript
