<?php

/**
 * OS edition controller.
 *
 * @category   apps
 * @package    edition
 * @subpackage controllers
 * @author     ClearCenter <developer@clearcenter.com>
 * @copyright  2012 ClearCenter
 * @license    http://www.clearcenter.com/app_license ClearCenter license
 * @link       http://www.clearcenter.com/support/documentation/clearos/edition/
 */

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
 * @copyright  2012 ClearCenter
 * @license    http://www.clearcenter.com/app_license ClearCenter license
 * @link       http://www.clearcenter.com/support/documentation/clearos/edition/
 */

class Edition extends ClearOS_Controller
{
    /**
     * Edition default controller.
     *
     * @return string
     */

    function index()
    {
        $this->lang->load('edition');
        $this->load->library('base/OS');
        $this->load->library('edition/Edition');

        // Load view data
        //---------------

        try {
            $os_name = $this->os->get_name();

            // Don't show upgrade on a Professional Edition
            if (preg_match('/ClearOS Professional/', $os_name))
                redirect($this->session->userdata['wizard_redirect']);
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        // Load views
        //-----------

        $this->page->view_form('edition/edition', $data, lang('edition_select_edition'));
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
                if ($this->input->post('edition') === 'professional') {
                    $this->edition->set('professional');
                    // FIXME
                    $this->session->set_userdata('os_name', 'ClearOS Professional');
                } else {
                    $this->edition->set('community');
                    // FIXME
                    $this->session->set_userdata('os_name', 'ClearOS Community');
                }
            }
            echo json_encode(Array('code' => 0));

        } catch (Exception $e) {
            echo json_encode(Array('code' => clearos_exception_code($e), 'errmsg' => clearos_exception_message($e)));
        }
    }

}
