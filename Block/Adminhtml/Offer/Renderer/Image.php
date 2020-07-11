<?php

namespace Dnd\Offer\Block\Adminhtml\Offer\Renderer;

/**
 * Class Image
 *
 * @author Yannick Waelkens <yannick.waelkens@cgi.com>
 */
class Image extends \Magento\Framework\Data\Form\Element\Image
{
    /**
     * @return string
     */
    protected function _getDeleteCheckbox()
    {
        $html = '';
        if ($this->getValue()) {
            $label = __('Delete Image');
            $html  .= '<span class="delete-image">';
            $html  .= '<input style="margin: auto;" type="checkbox"' .
                ' name="' .
                $this->getName() .
                '[delete]" value="1" class="checkbox"' .
                ' id="' .
                $this->getHtmlId() .
                '_delete"' .
                ($this->getDisabled() ? ' disabled="disabled"' : '') .
                '/>';
            $html  .= '<label for="' . $this->getHtmlId() .
                '_delete"' . ($this->getDisabled() ? ' class="disabled"' : '') . '> ' .
                $label .
                '</label>';
            $html  .= $this->_getHiddenInput();
            $html  .= '</span>';
        }
        $html .= '<style>#author_image_image, #post_image_image{ position: relative;top: 6px }</style>';

        return $html;
    }

}
