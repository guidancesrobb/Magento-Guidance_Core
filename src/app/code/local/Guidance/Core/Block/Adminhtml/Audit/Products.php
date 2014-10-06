<?php
/**
 * Guidance Core Module
 *
 * @author    Guidance Magento Team <magento@guidance.com>
 * @category  Guidance
 * @package   Guidance_Core
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Guidance_Core_Block_Adminhtml_Audit_Products
    extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Cached collection counts
     * @var array
     */
    protected $_counts;

    /**
     * Get collection sizes
     * 
     * @return array
     */
    public function getCounts()
    {
        if (is_null($this->_counts)) {
            $this->_counts = array();
            foreach (Mage::getModel('catalog/product_type')->getTypes() as $typeId => $data) {
                $this->_counts[$this->__($data['label'])] = Mage::getModel('catalog/product')
                    ->getCollection()
                    ->addAttributeToFilter('type_id', $typeId)
                    ->getSize();
            }
        }
        return $this->_counts;
    }
}
