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

        if (!$this->session->userdata('wizard')) {
            redirect('/edition/display');
            return;
        }

        // Load view data
        //---------------

        try {
            $selected = $this->edition->get();
            if ($selected !== FALSE)
                $selected['css_url_full_path'] = clearos_theme_url($this->session->userdata['theme']) . '/css/' . $selected['theme'];
            $data['selected'] = json_encode($selected);
            $data['editions'] = $this->edition->get_editions();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('edition/edition', $data, lang('edition_select_edition'));
    }

    /**
     * Edition display controller.
     *
     * @return view
     */

    function display()
    {
        $this->lang->load('edition');
        $this->load->library('edition/Edition');

        if ($this->session->userdata('wizard')) {
            redirect('/edition');
            return;
        }

        // Load view data
        //---------------

        try {
            $data['selected'] = $this->edition->get();
            $data['editions'] = $this->edition->get_editions();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('edition/selected', $data, lang('edition_currently_running'));
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
}
