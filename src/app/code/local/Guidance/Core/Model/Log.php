<?php
/**
 * Guidance Core Module
 *
 * @author    Guidance Magento Team <magento@guidance.com>
 * @category  Guidance
 * @package   Guidance_Core
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Guidance_Core_Model_Log extends Mage_Core_Model_Abstract
{
    /**
     * Initialize model and table
     * 
     * @return void
     */
    protected function _construct()
    {
        $this->_init('guidance_core/log');
    }
}
     