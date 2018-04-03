<?php
namespace Mageinn\Vendor\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Info
 * @package Mageinn\Vendor\Model\ResourceModel
 */
class Info extends AbstractDb
{
    /**
     * Define main table
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mageinn_vendor_information', 'entity_id');
    }
}