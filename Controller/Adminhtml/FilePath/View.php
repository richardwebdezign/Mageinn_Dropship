<?php
namespace Iredeem\Vendor\Controller\Adminhtml\FilePath;

/**
 * Class View
 * @package Iredeem\Vendor\Controller\Adminhtml\BatchRows
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
     * @var \Iredeem\Vendor\Model\Batch
     */
    private $batchModel;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Iredeem\Vendor\Model\Batch $batchModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Registry $registry,
        \Iredeem\Vendor\Model\Batch $batchModel
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
            $this->_layoutFactory->create()
                ->createBlock(
                    $this->_getGridClass($this->registry),
                    'batches.destinations'
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
        $model = $registry->registry('iredeem_batch');
        if (!$model) {
            $id = (int)$this->getRequest()->getParam('id', false);
            $model = $this->batchModel;

            if ($id) {
                $model->load($id);
            }

            $registry->register('iredeem_batch', $model);
            if ($model->getType() == \Iredeem\Vendor\Model\Source\BatchType::IREDEEM_VENDOR_BATCH_TYPE_IMPORT) {
                $registry->register('current_batch_type', \Iredeem\Vendor\Model\Batch::BATCH_TYPE_VIEW_IMPORT);
            } else {
                $registry->register('current_batch_type', \Iredeem\Vendor\Model\Batch::BATCH_TYPE_VIEW_EXPORT);
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
        if ($registry->registry('current_batch_type') == \Iredeem\Vendor\Model\Batch::BATCH_TYPE_VIEW_IMPORT) {
            $gridClass = \Iredeem\Vendor\Block\Adminhtml\Batch\View\Import\Sources\Grid::class;
        } else {
            $gridClass = \Iredeem\Vendor\Block\Adminhtml\Batch\View\Export\Destinations\Grid::class;
        }

        return $gridClass;
    }
}
