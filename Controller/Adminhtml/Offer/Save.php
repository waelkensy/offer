<?php

namespace Dnd\Offer\Controller\Adminhtml\Offer;

use Dnd\Offer\Model\OfferFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Session;
use Magento\Catalog\Model\ImageUploader;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Add
 *
 * @author Yannick Waelkens <yannick.waelkens@cgi.com>
 */
class Save extends Action
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
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * Edit constructor.
     *
     * @param Context       $context
     * @param OfferFactory  $offerFactory
     * @param PageFactory   $resultPageFactory
     * @param Session       $adminSession
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        OfferFactory $offerFactory,
        PageFactory $resultPageFactory,
        Session $adminSession,
        ImageUploader $imageUploader
    ) {
        $this->offerFactory      = $offerFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->adminSession      = $adminSession;
        $this->imageUploader     = $imageUploader;

        parent::__construct($context);
    }

    public function execute()
    {
        $postData                  = $this->getRequest()->getParam('offers');
        $postData['categories_id'] = $this->formatCategoryData($postData['categories']);
        unset($postData['categories']);
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!empty($postData)) {
            $model = $this->offerFactory->create();

            if (isset($postData['image'][0]['name']) && isset($postData['image'][0]['tmp_name'])) {
                $postData['image'] = $postData['image'][0]['name'];
                $this->imageUploader->moveFileFromTmp($postData['image']);
            } elseif (isset($postData['image'][0]['name']) && !isset($postData['image'][0]['tmp_name'])) {
                $postData['image'] = $postData['image'][0]['name'];
            } else {
                $postData['image'] = '';
            }

            if (!empty($postData["id"])) {
                $model->load($postData["id"]);
            }
            $model->addData($postData);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('The Offer has been saved.'));

                return $resultRedirect->setPath('dndoffer/*/add', ['id' => $model->getId(), '_current' => true]);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the offer.'));
            }

            $resultRedirect->setPath('dndoffer/*/index');

            return $resultRedirect;
        }
    }

    /**
     * formatCategoryData
     *
     * @param array $categories
     *
     * @return string
     */
    protected function formatCategoryData(array $categories)
    {
        return implode(",", $categories);
    }
}
