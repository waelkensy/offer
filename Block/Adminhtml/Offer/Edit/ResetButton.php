<?php

namespace Dnd\Offer\Block\Adminhtml\Offer\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ResetButton
 *
 * @author Yannick Waelkens <yannick.waelkens@cgi.com>
 */
class ResetButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label'      => __('Reset'),
            'class'      => 'reset',
            'on_click'   => 'location.reload();',
            'sort_order' => 30
        ];
    }
}
