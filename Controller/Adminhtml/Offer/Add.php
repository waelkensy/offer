<?php

namespace Dnd\Offer\Controller\Adminhtml\Offer;

use Dnd\Offer\Model\OfferFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Add
 *
 * @author Yannick Waelkens <yannick.waelkens@cgi.com>
 */
class Add extends Action
{
    protected $resultPageFactory = false;

    /**
     * Post factory
     *
     * @var OfferFactory
     */
    protected $offerFactory;


    /**
     * @var Session
     */
    private $adminSession;

    /**
     * Edit constructor.
     *
     * @param Context      $context
     * @param OfferFactory $offerFactory
     * @param PageFactory  $resultPageFactory
     * @param Session      $adminSession
     */
    public function __construct(
        Context $context,
        OfferFactory $offerFactory,
        PageFactory $resultPageFactory,
        Session $adminSession
    ) {
        $this->offerFactory = $offerFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->adminSession = $adminSession;

        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->offerFactory->create();
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This offer record no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Page|Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Offer'));
        $title = $model->getId() ? $model->getLabel() : __('New Offer');
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }

    /**
     * _isAllowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dnd_Offer::offer_instance');
    }
}
