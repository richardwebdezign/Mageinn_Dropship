<?php
/**
 * Mageinn
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageinn.com license that is
 * available through the world-wide-web at this URL:
 * https://mageinn.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 */
namespace Mageinn\Dropship\Block\Adminhtml\Batch\View\Export;

/**
 * Class Destinations
 * @package Mageinn\Dropship\Block\Adminhtml\Batch\View\Export
 */
class Destinations extends \Mageinn\Dropship\Block\Adminhtml\Batch\View\AbstractBatchDetails
{

    /**
     * @return \Magento\Backend\Block\Widget\Grid\Extended|\Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getGrid()
    {
        if (null === $this->_grid) {
            $this->_grid = $this->getLayout()->createBlock(
                \Mageinn\Dropship\Block\Adminhtml\Batch\View\Export\Destinations\Grid::class,
                'batches.destinations'
            );
        }
        return $this->_grid;
    }
}
