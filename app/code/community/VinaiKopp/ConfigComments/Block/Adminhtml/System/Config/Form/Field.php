<?php

class VinaiKopp_ConfigComments_Block_Adminhtml_System_Config_Form_Field
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = parent::_getElementHtml($element);
        if ($element->getTooltip()) {
            $html .= '</div></div>';
        }
        
        // Add comment code
        $html .= <<<EOT
<span class="vinaikopp-comments" ng-controller="FieldCtrl" ng-cloak>
    <span ng-mouseenter="showPopup(\$event, '{$element->getId()}')">
        [<a href="#">{{getComments('{$element->getId()}').length}}</a>]
    </span>
</span>
EOT;
        if ($element->getTooltip()) {
            $html .= '<div><div>';
        }
        
        return $html;
    }
} 