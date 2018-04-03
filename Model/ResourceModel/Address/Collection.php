<?php
namespace Mageinn\Vendor\Model\ResourceModel\Address;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use \Mageinn\Vendor\Model\Address;
use \Mageinn\Vendor\Model\ResourceModel\Address as ResourceModelAddress;

/**
 * Class Collection
 *
 * @package Mageinn\Vendor\Model\ResourceModel\Address
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Address::class, ResourceModelAddress::class);
        $this->_map['fields']['entity_id'] = 'main_table.entity_id';
    }
}