<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this Module to
 * newer versions in the future.
 *
 * @category   Magento
 * @package    VinaiKopp_ConfigComments
 * @copyright  Copyright (c) 2014 Vinai Kopp http://netzarbeiter.com
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


class VinaiKopp_ConfigComments_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getElementComments($elements)
    {
        $comments = $paths = array();

        /** @var Varien_Data_Form_Element_Abstract $element */
        foreach ($elements as $element) {
            if ($element->getType() == 'fieldset') {
                $this->_mergeComments($comments, $this->getElementComments($element->getElements()));
                continue;
            }
            $paths[] = $element->getId();
        }

        $this->_mergeComments($comments, $this->_loadComments($paths));

        return $comments;
    }

    protected function _mergeComments(&$commentTarget, $commentSource)
    {
        foreach ($commentSource as $path => $comments) {
            if (! isset($commentTarget[$path])) {
                $commentTarget[$path] = array();
            }
            $commentTarget[$path] += $comments;
        }
    }

    protected function _loadComments($paths)
    {
        if (! is_array($paths)) {
            $paths = array($paths);
        }
        $comments = array();
        $collection = Mage::getResourceModel('vinaikopp_configcomments/comment_collection')
            ->addFieldToFilter('path', array('in' => $paths))
            ->setOrder('sort_order', 'ASC');
        /** @var VinaiKopp_ConfigComments_Model_Comment[] $collection */
        foreach ($collection as $comment) {
            $path = $comment->getPath();
            if (! isset($comments[$path])) {
                $comments[$path] = array();
            }
            $comments[$path][] = array(
                'text' => $comment->getComment(),
                'author' => $comment->getAuthor()
            );
        }

        return $comments;
    }

    public function addCommentsToFields($elements)
    {
        foreach ($elements as $element) {
            /** @var Varien_Data_Form_Element_Abstract $element */
            $element->addClass('ng-non-bindable');
            if ($element->getType() == 'fieldset') {
                $this->addCommentsToFields($element->getElements());
                continue;
            }
            $afterElementHtml = $element->getAfterElementHtml();

            if ($element->getTooltip()) {
                $afterElementHtml .= '</div></div>';
            }

            // Add comment code
            $afterElementHtml .= <<<EOT
<span class="vinaikopp-comments" ng-controller="FieldCtrl" ng-cloak>
    <span ng-click="showPopup(\$event, '{$element->getId()}')">
        [<a href="#">{{getComments('{$element->getId()}').length}}</a>]
    </span>
</span>
EOT;
            if ($element->getTooltip()) {
                $afterElementHtml .= '<div><div>';
            }
            $element->setAfterElementHtml($afterElementHtml);
        }
    }
}
