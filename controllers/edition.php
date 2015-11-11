<?php

/**
 * OS edition controller.
 *
 * @category   apps
 * @package    edition
 * @subpackage controllers
 * @author     ClearCenter <developer@clearcenter.com>
 * @copyright  2015 ClearCenter
 * @license    http://www.clearcenter.com/app_license ClearCenter license
 * @link       http://www.clearcenter.com/support/documentation/clearos/edition/
 */

///////////////////////////////////////////////////////////////////////////////
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

use \clearos\apps\base\Install_Wizard as Install_Wizard;

// Exceptions
//-----------

use \Exception as Exception;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * OS edition controller.
 *
 * @category   apps
 * @package    edition
 * @subpackage controllers
 * @author     ClearCenter <developer@clearcenter.com>
 * @copyright  2015 ClearCenter
 * @license    http://www.clearcenter.com/app_license ClearCenter license
 * @link       http://www.clearcenter.com/support/documentation/clearos/edition/
 */

class Edition extends ClearOS_Controller
{
    /**
     * Edition default controller.
     *
     * @return view
     */

    function index()
    {
        $this->lang->load('edition');
        $this->load->library('edition/Edition');
        $this->load->library('base/Install_Wizard');
        $this->load->library('base/Script', Install_Wizard::SCRIPT_UPGRADE);

        if ($this->script->is_running()) {
            // If wizard update is running still, just put on hold
            redirect('/edition/updating');
            return;
        }

        // Load view data
        //---------------

        try {
            $selected = $this->edition->get();

            if (!$this->session->userdata('wizard') && $selected) {
                redirect('/edition/display');
                return;
            }

            $options = array();

            if ($selected !== FALSE)
                $selected['css_url_full_path'] = clearos_theme_url($this->session->userdata['theme']) . '/css/' . $selected['theme'];
            else
                $options['type'] = MY_Page::TYPE_SPOTLIGHT;

            $data['selected'] = json_encode($selected);
            $data['editions'] = $this->edition->get_editions();
            // FIXME -- be more selective about which editions.  Show all for now.
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('edition/edition', $data, lang('edition_select_edition'), $options);
    }

    /**
     * Edition display controller.
     *
     * @param boolean $display_reset Show reset option
     * @return view
     */

    function display($display_reset = FALSE)
    {
        $this->lang->load('edition');
        $this->load->library('edition/Edition');

        if ($this->session->userdata('wizard')) {
            redirect('/edition');
            return;
        }

        $selected = $this->edition->get();

        if (!$selected) {
            redirect('/edition');
            return;
        }
        // Only display upgrade unregistered or Community Systems
        if (!$this->edition->is_registered())
            $display_reset = TRUE;
        else if (preg_match('/community/', $selected['class']) && $display_reset !== FALSE)
            $display_reset = TRUE;

        // Load view data
        //---------------

        try {
            $data['selected'] = $selected;
            $data['editions'] = $this->edition->get_editions();
            $data['display_reset'] = $display_reset;
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('edition/selected', $data, lang('edition_app_name'));
    }

    /**
     * Do reset edition.
     *
     * @return view
     */

    function do_reset()
    {
        $this->load->library('edition/Edition');

        $this->edition->reset();

        redirect('/edition');
    }

    /**
     * Reset edition.
     *
     * @param String $force String to allow reset
     *
     * @return view
     */

    function reset($force = FALSE)
    {
        $this->lang->load('edition');
        $this->load->library('edition/Edition');

        $selected = $this->edition->get();

        if (!$this->edition->is_registered()) {
            // Allow un-registered systems to set edition
        } else if (preg_match('/community/', $selected['class'])) {
            // Allow reset on Community Editions
        } else if (!preg_match('/community/', $selected['class']) && $display_reset == 'edition_reset') {
            // Allow reset with specific key
        } else {
            $this->page->set_message(lang('edition_no_reset'), 'warning');
            redirect('/edition/display');
            return;
        }

        $confirm_uri = '/app/edition/do_reset';
        $cancel_uri = '/app/edition';
        $items = array($username);

        $this->page->view_confirm(lang('edition_confirm_edition_change'), $confirm_uri, $cancel_uri, $items);
    }

    /**
     * Ajax Edition Update
     *
     * @return JSON
     */

    function update_edition()
    {
        clearos_profile(__METHOD__, __LINE__);

        header('Cache-Control: no-cache, must-revalidate');
        header('Content-type: application/json');

        $this->load->library('edition/Edition');

        try {
            // Handle update
            //--------------
            if ($this->input->post('edition')) {
                $this->edition->set($this->input->post('edition'));
                $info = $this->edition->get();
                $this->session->set_userdata('os_name', $info['name']);
            }
            echo json_encode(
                Array(
                    'code' => 0,
                    'short_name' => $info['short_name'],
                    'css_url' => clearos_theme_url($this->session->userdata['theme']) . '/css/' . $info['theme'],
                )
            );

        } catch (Exception $e) {
            echo json_encode(Array('code' => clearos_exception_code($e), 'errmsg' => clearos_exception_message($e)));
        }
    }

    /**
     * Edition updating controller
     *
     * @return view
     */

    function updating()
    {
        clearos_profile(__METHOD__, __LINE__);

        clearos_load_language('edition');

        $this->page->view_form('edition/updating_library', $data, lang('edition_updating'));
    }

    /**
     * Abort software update
     *
     * @return view
     */

    function abort_update()
    {
        clearos_profile(__METHOD__, __LINE__);

        $this->load->library('base/Install_Wizard');
        $this->install_wizard->abort_update_script();
        redirect('/edition');
    }
}
