<?php

namespace Dnd\Offer\Model\ResourceModel;

use Dnd\Offer\Model\OfferFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Offer
 *
 * @author Yannick Waelkens <yannick.waelkens@cgi.com>
 */
class Offer extends AbstractDb
{
    /**
     * @var OfferFactory
     */
    private $offerFactory;

    /**
     * Offer constructor.
     *
     * @param OfferFactory $offerFactory
     * @param Context      $context
     */
    public function __construct(
        OfferFactory $offerFactory,
        Context $context
    ) {
        $this->offerFactory = $offerFactory;

        parent::__construct($context);
    }

    /**
     * define main table
     *
     */
    protected function _construct()
    {
        $this->_init('offers', 'id');
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    protected function _afterSave(AbstractModel $object)
    {
        $this->saveCategoryRelation($object);

        return parent::_afterSave($object);
    }

    /**
     * getCategoryIds
     *
     * @param \Dnd\Offer\Model\Offer $offer
     *
     * @return array
     */
    public function getCategoryIds(\Dnd\Offer\Model\Offer $offer)
    {
        $tableName = $this->getTable('offers_category');

        $adapter = $this->getConnection();
        $select  = $adapter->select()->from(
            $tableName,
            'category_id'
        )
            ->where(
                'offer_id = ?',
                (int)$offer->getId()
            );

        return $adapter->fetchCol($select);
    }

    /**
     * saveCategoryRelation
     *
     * @param \Dnd\Offer\Model\Offer $offer
     *
     * @return $this|bool
     * @throws LocalizedException
     */
    public function saveCategoryRelation(\Dnd\Offer\Model\Offer $offer)
    {
        $id         = $offer->getId();
        $categories = explode(",", $offer->getCategoriesId());
        $tableName  = $this->getTable('offers_category');

        if ($categories === null) {
            return $this;
        }

        $oldCategoryIds = $offer->getCategoryIds();
        $insert         = array_diff($categories, $oldCategoryIds);
        $delete         = array_diff($oldCategoryIds, $categories);
        $adapter        = $this->getConnection();

        if (!empty($delete)) {
            $condition = ['category_id IN(?)' => $delete, 'offer_id=?' => $id];
            $adapter->delete($tableName, $condition);
        }

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $categoryId) {
                $data[] = [
                    'offer_id'    => (int)$id,
                    'category_id' => (int)$categoryId
                ];
            }
            $adapter->insertMultiple($tableName, $data);
        }

        return $this;
    }

    /**
     * getOfferIdByCategoryId
     *
     * @param $categoryId
     *
     * @return array
     */
    public function getOfferIdByCategoryId($categoryId)
    {
        $tableName = $this->getTable('offers_category');
        $adapter   = $this->getConnection();

        $select = $adapter->select()
            ->from($tableName, 'offer_id')
            ->where('category_id' . " = ?", $categoryId);

        return $adapter->fetchAll($select);
    }
}
