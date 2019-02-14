<?php

namespace Mageinn\Dropship\Controller\Adminhtml\Stock;

use \Magento\Backend\App\Action;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

/**
 * Vendor Index Action
 * @package Mageinn\Dropship\Controller\Adminhtml\Vendor
 * @codeCoverageIgnore Controller functions don't need UT
 */
class Index extends Action
{
    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        parent::__construct($context);
        $this->resultFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create();
        $resultPage->setActiveMenu('Mageinn_Dropship::vendor_stock');
        $resultPage->addBreadcrumb(__('Stock'), __('Stock'));
        $resultPage->addBreadcrumb(__('Import/Export'), __('Import/Export'));
        $resultPage->getConfig()->getTitle()->prepend(__('Import/Export Product Stock '));

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageinn_Dropship::product_stock');
    }
}