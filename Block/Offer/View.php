<?php

namespace Dnd\Offer\Block\Offer;

use Dnd\Offer\Helper\OfferHelper;
use Dnd\Offer\Model\ResourceModel\Offer;
use Dnd\Offer\Model\ResourceModel\Offer\CollectionFactory;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class View extends \Magento\Framework\View\Element\Template
{

    /**
     * @var CollectionFactory
     */
    private $offerCollection;
    /**
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @var Offer
     */
    private $offerResourceModel;

    public function __construct(
        CollectionFactory $offerCollection,
        Resolver $layerResolver,
        Offer $offerResourceModel,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->offerCollection    = $offerCollection;
        $this->layerResolver      = $layerResolver;
        $this->offerResourceModel = $offerResourceModel;
    }

    /**
     * retrieve offers for current category
     *
     * @return Offer\Collection
     */
    public function getOffers()
    {
        $categoryId      = $this->layerResolver->get()->getCurrentCategory()->getId();
        $offerIds        = $this->offerResourceModel->getOfferIdByCategoryId($categoryId);
        $offerCollection = $this->offerCollection->create();
        $now             = new \DateTime();

        $offerCollection->addFieldToFilter('id', ['in' => $offerIds])
            ->addFieldToFilter('start_date', ['lteq' => $now->format('Y-m-d H:i:s')])
            ->addFieldToFilter('end_date', ['gteq' => $now->format('Y-m-d H:i:s')]);

        return $offerCollection;
    }

    /**
     * Return Media Path
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getMediaPath()
    {
        return $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . OfferHelper::BASE_PATH;
    }
}
