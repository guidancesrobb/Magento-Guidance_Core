<?php
/**
 * Guidance Core Module
 *
 * @author    Guidance Magento Team <magento@guidance.com>
 * @category  Guidance
 * @package   Guidance_Core
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Guidance_Core_Block_Adminhtml_Log_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{    
    /**
     * Initialize block
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('logGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir(Zend_Db_Select::SQL_DESC);
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepares log collection
     * 
     * @return Guidance_Core_Block_Adminhtml_Log_Grid
     */
    protected function _prepareCollection()
    {
        $this->setCollection(
            Mage::getModel('guidance_core/log')->getCollection()
        );
        return parent::_prepareCollection();
    }

    /**
     * Add columns
     * 
     * @return Guidance_Core_Block_Adminhtml_Log_Grid
     */
    protected function _prepareColumns()
    {
        $helper = $this->helper('guidance_core');
        $this->addColumn('id', array(
            'header' => $helper->__('ID'),
            'align'  =>'right',
            'width'  => '50px',
            'type'   => 'number',
            'index'  => 'id'
        ));            
        $this->addColumn('time', array(
            'header' => $helper->__('Time'),
            'index'  => 'time'
        ));
        $this->addColumn('module', array(
            'header' => $helper->__('Module Name'),
            'index'  => 'module'
        ));
        $this->addColumn('message', array(
            'header' => $helper->__('Log Message'),
            'index'  => 'message'
        ));
        $this->addColumn('log_level', array(
            'header'  => $helper->__('Log Level'),
            'index'   => 'log_level',
            'type'    =>  'options',
            'options' => array(
                Zend_Log::EMERG  => $this->__('Emergency'), // Emergency: system is unusable
                Zend_Log::ALERT  => $this->__('Alert'), // Alert: action must be taken immediately
                Zend_Log::CRIT   => $this->__('Critical'), // Critical: critical conditions
                Zend_Log::ERR    => $this->__('Error'), // Error: error conditions
                Zend_Log::WARN   => $this->__('Warning'), // Warning: warning conditions
                Zend_Log::NOTICE => $this->__('Notice'), // Notice: normal but significant condition
                Zend_Log::INFO   => $this->__('Informational'), // Informational: informational messages
                Zend_Log::DEBUG  => $this->__('Debug')  // Debug: debug messages
            )
        ));
        $this->addExportType('*/*/exportCsv', $helper->__('CSV')); 
        $this->addExportType('*/*/exportExcel', $helper->__('Excel'));
        return parent::_prepareColumns();
    }

    /**
     * Return row url for js event handlers
     *
     * @param Mage_Catalog_Model_Product|Varien_Object
     * @return string
     */
    public function getRowUrl($row)
    {
        return '#';
    }
}
