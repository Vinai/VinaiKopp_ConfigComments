<?php


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