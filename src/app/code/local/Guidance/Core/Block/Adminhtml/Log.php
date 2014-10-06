<?php
/**
 * Guidance Core Module
 *
 * @author    Guidance Magento Team <magento@guidance.com>
 * @category  Guidance
 * @package   Guidance_Core
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Guidance_Core_Block_Adminhtml_Log
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize block
     *
     * @return void
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_log';
        $this->_blockGroup = 'guidance_core';
        $this->_headerText = Mage::helper('guidance_core')->__('Logs');
        parent::__construct();
        $this->_removeButton('add');
        $this->_addButton('guidance_core_log_clear', array(
            'label'   => $this->__('Clear'),
            'onclick' => "setLocation('{$this->getUrl('*/guidance/logClear')}')",
            'class'   => 'delete'
        ));
    }
}
