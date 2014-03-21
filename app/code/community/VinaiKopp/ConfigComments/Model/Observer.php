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


class VinaiKopp_ConfigComments_Model_Observer
{
    public function coreBlockAbstractToHtmlAfter(Varien_Event_Observer $args)
    {
        /** @var Mage_Core_Block_Abstract $block */
        $block = $args->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_System_Config_Edit) {
            /** @var Varien_Data_Form $form */
            $form = $block->getChild('form')->getForm();
            $html = $args->getTransport()->getHtml();
            
            $updateUrl = Mage::helper('adminhtml')->getUrl('adminhtml/configComments/update')
                . 'form_key/' . Mage::getSingleton('core/session')->getFormKey();
            $initData = array(
                'comments'  => Mage::helper('vinaikopp_configcomments')->getElementComments($form->getElements()),
                'author'    => Mage::getSingleton('admin/session')->getUser()->getName(),
                'updateUrl' => $updateUrl,
            );
            
            // Well, ugly, but I don't see to get that class in there without a rewrite.
            $html = str_replace('<p class="note">', '<p class="note ng-non-bindable">', $html);
            
            $wrapper = $block->getLayout()->createBlock('core/template')
                ->setTemplate('vinaikopp/configcomments/wrapper.phtml')
                ->assign('blockHtml', $html)
                ->assign('initJson', Mage::helper('core')->jsonEncode($initData));
            $args->getTransport()->setHtml($wrapper->toHtml());
        }
    }
    
    public function adminhtmlBlockHtmlBefore(Varien_Event_Observer $args)
    {
        /** @var Mage_Core_Block_Abstract $block */
        $block = $args->getBlock();
        if ($block instanceof Mage_Adminhtml_Block_System_Config_Edit) {
            $form = $block->getChild('form')->getForm();
            Mage::helper('vinaikopp_configcomments')->addCommentsToFields($form->getElements());
        }
    }
} 