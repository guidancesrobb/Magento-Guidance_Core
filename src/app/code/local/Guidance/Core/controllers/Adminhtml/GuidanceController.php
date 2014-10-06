<?php
/**
 * Guidance Core Module
 *
 * @author    Guidance Magento Team <magento@guidance.com>
 * @category  Guidance
 * @package   Guidance_Core
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

/**
 * Audit controller
 */
class Guidance_Core_Adminhtml_GuidanceController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Display audit information
     *
     * @return void
     */
    public function auditAction()
    {
        $this
            ->_title($this->__('System'))
            ->_title($this->__('Guidance'))
            ->_title($this->__('Audit'))
            ->loadLayout()
            ->_setActiveMenu('system/guidance/audit')
            ->renderLayout();
    }

    /**
     * Display log information
     *
     * @return void
     */
    public function logAction()
    {
        $this
            ->_title($this->__('Logs'))
            ->_title($this->__('Guidance'))
            ->_title($this->__('Audit'))
            ->loadLayout()
            ->_setActiveMenu('system/guidance/log')
            ->renderLayout();
    }

    /**
     * Truncates all db logs
     *
     * @return void
     */
    public function logClearAction()
    {
        try {
            Mage::getResourceModel('guidance_core/log')->truncate();
            Mage::getSingleton('core/session')->addSuccess(
                $this->__('Logs cleared')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addSuccess(
                $this->__('An error occured when clearing the logs')
            );
        }
        $this->_redirect('*/*/log');
    }
}
