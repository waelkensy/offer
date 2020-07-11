<?php

namespace Dnd\Offer\Model\ResourceModel\Offer;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @author Yannick Waelkens <yannick.waelkens@cgi.com>
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    protected $_eventPrefix = 'dnd_offer_offer_collection';
    protected $_eventObject = 'offer_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dnd\Offer\Model\Offer', 'Dnd\Offer\Model\ResourceModel\Offer');
    }

    public function getOfferByDateAndCategory()
    {
        return $this->getConnection();
    }
}
