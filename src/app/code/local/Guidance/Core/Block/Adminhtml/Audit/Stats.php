<?php
/**
 * Guidance Core Module
 *
 * @author    Guidance Magento Team <magento@guidance.com>
 * @category  Guidance
 * @package   Guidance_Core
 * @copyright Copyright (c) 2014 Guidance Solutions (http://www.guidance.com)
 */

class Guidance_Core_Block_Adminhtml_Audit_Stats
    extends Mage_Adminhtml_Block_Widget_Container
{
    /**
     * Cached collection counts
     * @var array
     */
    protected $_counts;

    /**
     * Collections used in statistics
     * @var array
     */
    protected $_countCollections = array(        
        'Products'               => 'catalog/product',
        'Content pages'          => 'cms/page',
        'Content blocks'         => 'cms/block',
        'Newsletter subscribers' => 'newsletter/subscriber',
        'Customers'              => 'customer/customer',
        'Customer groups'        => 'customer/group',
        'Sales rules'            => 'salesrule/rule',
        'Sales rule coupons'     => 'salesrule/coupon',
        'Quotes'                 => 'sales/quote',
        'Orders'                 => 'sales/order',
        'Invoices'               => 'sales/order_invoice',
        'Shipments'              => 'sales/order_shipment'
    );

    /**
     * Get collection sizes
     * 
     * @return array
     */
    public function getCounts()
    {
        if (is_null($this->_counts)) {
            $this->_counts = array();
            foreach ($this->_countCollections as $title => $model) {
                $this->_counts[$this->__($title)] = Mage::getModel($model)
                    ->getCollection()->getSize();
            }
        }
        return $this->_counts;
    }
}
