<?php
/**
 * Guidance Core Module
 *
 * @author    Guidance Magento Team <magento@guidance.com>
 * @category  Guidance
 * @package   Guidance_Core
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Guidance_Core_Model_Resource_Log extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('guidance_core/log', 'log');
    }

    /**
     * Nuke it
     * 
     * @return Guidance_Core_Model_Resource_Log
     */
    public function truncate()
    {
        $this->_getWriteAdapter()->query(
            'TRUNCATE TABLE ' . $this->getMainTable()
        );
        return $this;
    }
}
     