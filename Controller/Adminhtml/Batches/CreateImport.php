<?php
namespace Mageinn\Dropship\Controller\Adminhtml\Batches;

/**
 * Class CreateImport
 * @package Mageinn\Dropship\Controller\Adminhtml\Batches
 */
class CreateImport extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPage;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * CreateImport constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPage = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPage->create();
        $resultPage->setActiveMenu('Mageinn_Dropship::vendor_batches')
            ->addBreadcrumb(__('Create Tracking Import Batch'), __('Create Tracking Import Batch'));
        $resultPage->getConfig()->getTitle()->prepend(__('Create Tracking Import Batch'));

        return $resultPage;
    }
}
