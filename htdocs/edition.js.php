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

$(document).ready(function() {

    if ($('#edition-selected').length != 0) {
        var edition = jQuery.parseJSON($('#edition-selected').html());
        // console.log(edition);
        $("#" + edition.dom_id).hide();
        $("#" + edition.dom_id).data('selected', 1);
        $("#" + edition.dom_id).prev().show();
        $("#theme-os-css").attr("href", edition.css_url_full_path);
        $("#theme-clearos-os-name").html(edition.short_name);
        $(".edition-help-" + edition.class).show();
    }

    $(".edition-selector").on('click', function(e) {
        e.preventDefault();
        $(".edition-selector").show();
        $(".edition-selector").data('selected', 0);
        $(".edition-label").hide();
        $("#" + this.id).hide();
        $("#" + this.id).data('selected', 1);
        $("#" + this.id).prev().show();
        update_edition($('#' + this.id).data('conf'));
    });
    $(".edition-info-box").on('mouseenter', function(e) {
        $(".edition-info").hide();
        if (this.id.match('.*community.*')) {
            $(".edition-help-community").show();
        } else if (this.id.match('.*business.*')) {
            $(".edition-help-business").show();
        } else if (this.id.match('.*home.*')) {
            $(".edition-help-home").show();
        }
    });
    $(".edition-info-box").on('mouseleave', function(e) {
        $(".edition-info-box").each(function( index ) {
            if ($(this).find('a').data('selected') != undefined && $(this).find('a').data('selected') == 1) {
                $(".edition-info").hide();
                if (this.id.match('.*community.*')) {
                    $(".edition-help-community").show();
                } else if (this.id.match('.*business.*')) {
                    $(".edition-help-business").show();
                } else if (this.id.match('.*home.*')) {
                    $(".edition-help-home").show();
                }
            }
        });
    });

    // Wizard previous/next button handling
    //-------------------------------------

    $('#wizard_nav_next').on('click', function(e) {
        var select_made = false;
        $(".edition-info-box").each(function( index ) {
            if ($(this).find('a').data('selected') != undefined && $(this).find('a').data('selected') == 1)
                select_made = true;
        });
        if (!select_made) {
            e.preventDefault();
            clearos_modal_infobox_open('wizard_next_showstopper');
            return;
        }
    });

    if ($(location).attr('href').match('.*edition\/updating.*') != null)
        wizard_update_is_updating();
});

/* Update edition */

function update_edition(edition) {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/app/edition/update_edition',
        data: 'ci_csrf_token=' + $.cookie('ci_csrf_token') + '&edition=' + encodeURIComponent(edition),
        success: function(data) {
            if (data.code != 0)
                clearos_dialog_box('error', lang_warning, data.errmsg);
            $("#theme-os-css").attr("href", data.css_url);
            $("#theme-clearos-os-name").html(data.short_name);
            if ($('#wizard_nav_next').length == 0)
                clearos_modal_infobox_open('change_edition_reregister');
        },
        error: function(xhr, text, err) {
            clearos_dialog_box('error', lang_warning, xhr.responseText.toString());
        }
    });
}

function wizard_update_is_updating() {
    $.ajax({
        url: '/app/base/wizard/is_wizard_upgrade_running',
        method: 'GET',
        dataType: 'json',
        success : function(json) {
            if (json.state != 1) {
                window.location = '/app/edition';
                return;
            }
            window.setTimeout(wizard_update_is_updating, 1000);
        },
        error: function(xhr, text, err) {
            window.location = '/app/edition/abort_update';
            return;
        }
    });
}
// vim: ts=4 syntax=javascript
