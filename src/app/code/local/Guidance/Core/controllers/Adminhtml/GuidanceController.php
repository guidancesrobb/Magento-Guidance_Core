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
}
