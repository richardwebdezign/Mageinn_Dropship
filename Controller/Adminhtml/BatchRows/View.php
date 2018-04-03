<?php
namespace Mageinn\Vendor\Controller\Adminhtml\BatchRows;

/**
 * Class View
 * @package Mageinn\Vendor\Controller\Adminhtml\BatchRows
 */
class View extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $_resultRawFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $_layoutFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Mageinn\Vendor\Model\Batch
     */
    private $batchModel;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Mageinn\Vendor\Model\Batch $batchModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Registry $registry,
        \Mageinn\Vendor\Model\Batch $batchModel
    ) {
        $this->_resultRawFactory = $resultRawFactory;
        $this->_layoutFactory = $layoutFactory;
        $this->registry = $registry;
        $this->batchModel = $batchModel;

        parent::__construct($context);
    }

    /**
     * Action for when you refresh the batch rows grid
     *
     * @return $this|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Raw|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $item = $this->_initItem($this->registry);
        if (!$item) {
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath(
                'sales/batches/view' . $this->registry->registry('current_batch_type'),
                ['_current' => true]
            );
        }

        /** @var \Magento\Framework\Controller\Result\Raw $result */
        $result = $this->_resultRawFactory->create();
        $result->setContents(
            $this->_layoutFactory->create()->createBlock(
                $this->_getGridClass($this->registry),
                'batches.data.rows'
            )->toHtml()
        );

        return $result;
    }

    /**
     * @param \Magento\Framework\Registry $registry
     * @return mixed
     */
    protected function _initItem($registry)
    {
        $model = $registry->registry('mageinn_batch');
        if (!$model) {
            $id = (int)$this->getRequest()->getParam('id', false);
            $model = $this->batchModel;

            if ($id) {
                $model->load($id);
            }

            $registry->register('mageinn_batch', $model);
            if ($model->getType() == \Mageinn\Vendor\Model\Source\BatchType::MAGEINN_VENDOR_BATCH_TYPE_IMPORT) {
                $registry->register('current_batch_type', \Mageinn\Vendor\Model\Batch::BATCH_TYPE_VIEW_IMPORT);
            } else {
                $registry->register('current_batch_type', \Mageinn\Vendor\Model\Batch::BATCH_TYPE_VIEW_EXPORT);
            }
        }

        return $model;
    }

    /**
     * @param \Magento\Framework\Registry $registry
     * @return string
     */
    protected function _getGridClass($registry)
    {
        if ($registry->registry('current_batch_type') == \Mageinn\Vendor\Model\Batch::BATCH_TYPE_VIEW_IMPORT) {
            $gridClass = \Mageinn\Vendor\Block\Adminhtml\Batch\View\Import\BatchRows\Grid::class;
        } else {
            $gridClass = \Mageinn\Vendor\Block\Adminhtml\Batch\View\Export\BatchRows\Grid::class;
        }

        return $gridClass;
    }
}