<?php
/**
 * Guidance Core Module
 *
 * @author    Guidance Magento Team <magento@guidance.com>
 * @category  Guidance
 * @package   Guidance_Core
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Guidance_Core_Helper_Abstract extends Mage_Core_Helper_Abstract
{
    /**
     * Module name (used for logging)
     * 
     * @var string
     */
    protected $_moduleName = 'guidance_core';

    /**
     * Logs messages to database
     * 
     * @param  string  $message
     * @param  integer $logLevel
     * @return void
     */
    public function log($message, $logLevel = Zend_Log::DEBUG)
    {
        try {
            Mage::getModel('guidance_core/log')->setData(array(
                'time'      => now(),
                'module'    => $this->_moduleName,
                'message'   => $message,
                'log_level' => $logLevel
            ))->save();
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::log($message, $logLevel, $this->_moduleName . '.log', true);
        }
    }
}
