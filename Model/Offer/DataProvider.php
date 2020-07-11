<?php

namespace Dnd\Offer\Model\Offer;

use Dnd\Offer\Helper\OfferHelper;
use Dnd\Offer\Model\ResourceModel\Offer\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param string                $name
     * @param string                $primaryFieldName
     * @param string                $requestFieldName
     * @param CollectionFactory     $OfferCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param array                 $meta
     * @param array                 $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $OfferCollectionFactory,
        StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection   = $OfferCollectionFactory->create();
        $this->storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items            = $this->collection->getItems();
        $this->loadedData = [];
        $mediaUrl         = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        foreach ($items as $offer) {
            $offerData = $offer->getData();
            if (isset($offerData['image'])) {
                $imageName = $offerData['image'];
                unset($offerData['image']);
                $offerData['image'][0]['name'] = $imageName;
                $offerData['image'][0]['url']  = $mediaUrl . '' . OfferHelper::BASE_PATH . $imageName;
            }

            $this->loadedData[$offer->getId()]['offers']               = $offerData;
            $this->loadedData[$offer->getId()]['offers']["categories"] = $offer->getCategoryIds();
        }

        return $this->loadedData;
    }
}
