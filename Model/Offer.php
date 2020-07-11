<?php

namespace Dnd\Offer\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Offer
 *
 * @author Yannick Waelkens <yannick.waelkens@cgi.com>
 */
class Offer extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'dnd_offer';
    protected $_cacheTag = 'dnd_offer';
    protected $_eventPrefix = 'dnd_offer';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('Dnd\Offer\Model\ResourceModel\Offer');
    }

    /**
     * @inheritDoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getCategoryIds()
    {
        if (!$this->hasData('category_ids')) {
            $ids = $this->_getResource()->getCategoryIds($this);
            $this->setData('category_ids', $ids);
        }

        return (array) $this->_getData('category_ids');
    }
}
