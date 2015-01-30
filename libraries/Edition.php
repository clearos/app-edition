<?php

/**
 * OS Edition class.
 *
 * @category   apps
 * @package    edition
 * @subpackage libraries
 * @author     ClearCenter <developer@clearcenter.com>
 * @copyright  2015 ClearCenter
 * @license    http://www.clearcenter.com/app_license ClearCenter license
 * @link       http://www.clearcenter.com/support/documentation/clearos/edition/
 */

///////////////////////////////////////////////////////////////////////////////
// N A M E S P A C E
///////////////////////////////////////////////////////////////////////////////

namespace clearos\apps\edition;

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
// D E P E N D E N C I E S
///////////////////////////////////////////////////////////////////////////////

// Classes
//--------

use \clearos\apps\base\Configuration_File as Configuration_File;
use \clearos\apps\base\File as File;
use \clearos\apps\base\Folder as Folder;
use \clearos\apps\base\Product as Product;

clearos_load_library('base/Configuration_File');
clearos_load_library('base/File');
clearos_load_library('base/Folder');
clearos_load_library('base/Product');

// Exceptions
//-----------

use \Exception as Exception;

///////////////////////////////////////////////////////////////////////////////
// C L A S S
///////////////////////////////////////////////////////////////////////////////

/**
 * OS Edition class.
 *
 * @category   apps
 * @package    edition
 * @subpackage libraries
 * @author     ClearCenter <developer@clearcenter.com>
 * @copyright  2015 ClearCenter
 * @license    http://www.clearcenter.com/app_license ClearCenter license
 * @link       http://www.clearcenter.com/support/documentation/clearos/edition/
 */

class Edition extends Product
{
    ///////////////////////////////////////////////////////////////////////////////
    // C O N S T A N T S
    ///////////////////////////////////////////////////////////////////////////////

    const PATH_CONFIGLETS = '/etc/clearos/edition.d';

    ///////////////////////////////////////////////////////////////////////////////
    // M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////

    /**
     * Edition constructor.
     */

    public function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);
    }

    /**
     * Sets the Edition.
     * 
     * @param string $conf Configlet filename of the selected edition
     *
     * @return integer exit code
     * @throws Validation_Exception
     */

    public function set($conf)
    {
        clearos_profile(__METHOD__, __LINE__);

        try {
            $configlets = $this->_get_configlets();
            foreach ($configlets as $id => $edition) {
                if ($edition['configlet_file'] == $conf) {
                    // Set product file
                    $file = new File(self::PATH_CONFIGLETS . '/' . $edition['configlet_file']);
                    $file->copy_to(self::FILE_CONFIG);
                    break;
                }
            }
        } catch (Exception $e) {
            // Not fatal
        }
    }

    /**
     * Gets the Edition.
     * 
     * @return array the edition plus metadata
     * @throws Engine_Exception
     */

    public function get()
    {
        clearos_profile(__METHOD__, __LINE__);

        if ($this->get_name() == '') {
            return FALSE;
        } else {
            $configlets = $this->_get_configlets();
            foreach ($configlets as $edition) {
                if ($edition['name'] == $this->get_name())
                    return $edition; 
            }
            return FALSE;
        }
    }

    /**
     * Gets all available Editions.
     * 
     * @return array the edition plus metadata
     * @throws Engine_Exception
     */

    public function get_editions()
    {
        clearos_profile(__METHOD__, __LINE__);

        return $this->_get_configlets();
    }

    ///////////////////////////////////////////////////////////////////////////////
    // P R I V A T E   M E T H O D S
    ///////////////////////////////////////////////////////////////////////////////
    
    /**
     * Returns detailed edition configlets.
     *
     * @return array config details
     * @throws Engine_Exception
     */

    protected function _get_configlets()
    {
        clearos_profile(__METHOD__, __LINE__);

        // Load configlets
        //----------------

        $folder = new Folder(self::PATH_CONFIGLETS);

        $configs_list = $folder->get_listing();

        $configlets = array();

        foreach ($configs_list as $configlet) {
            if (! preg_match('/(.*)\.conf$/', $configlet, $match))
                continue;

            $configfile = new Configuration_File(self::PATH_CONFIGLETS . '/' . $configlet);

            try {
                $data = $configfile->load();
                $data['configlet_file'] = $match[1] . ".conf";
                $data['dom_id'] = strtolower($data['short_name']) . "_" . $data['software_id'];
            } catch (Exception $e) {
                // Shouldn't happen, but don't include if anything goes wrong
            }

            $configlets[$data['order_priority']] = $data;
        }

        ksort($configlets);

        return $configlets;
    }
}
