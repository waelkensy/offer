<?php

namespace Dnd\Offer\Controller\Adminhtml\Offer;

use Dnd\Offer\Model\OfferFactory;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;

/**
 * Class Delete
 *
 * @author Yannick Waelkens <yannick.waelkens@cgi.com>
 */
class Delete extends Action
{

    /**
     * @var OfferFactory
     */
    private $offerFactory;

    public function __construct(Action\Context $context, OfferFactory $offerFactory)
    {
        $this->offerFactory = $offerFactory;

        parent::__construct($context);
    }

    /**
     * @return Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $this->offerFactory->create()
                    ->load($id)
                    ->delete();
                $this->messageManager->addSuccessMessage(__('The Offer has been deleted.'));
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $resultRedirect->setPath('dndoffer_offer/*/edit', ['id' => $id]);

                return $resultRedirect;
            }
        } else {
            $this->messageManager->addErrorMessage(__('Offer to delete was not found.'));
        }

        $resultRedirect->setPath('dndoffer/offer/index');

        return $resultRedirect;
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
