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


class VinaiKopp_ConfigComments_Adminhtml_ConfigCommentsController
    extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config');
    }
    
    public function updateAction()
    {
        $raw = $this->getRequest()->getRawBody();
        try {
            $post = Mage::helper('core')->jsonDecode($raw);
        } catch (Exception $e) {
            $post = array();
        }
        if ($post) {
            $result = array('ok' => array(), 'fail' => array());
            $comment = Mage::getModel('vinaikopp_configcomments/comment');

            try {
                $comment->getResource()->beginTransaction();
                $comment->getCollection()
                    ->addFieldToFilter('path', $post['path'])
                    ->walk('delete');
                
                foreach ($post['comments'] as $i => $commentData) {
                    try {
                        $comment->setData(array(
                            'comment' => $commentData['text'],
                            'author' => $commentData['author'],
                            'path' => $post['path'],
                            'sort_order' => ($i+1) * 10
                        ))->save();
                        $result['ok'][] = $i;
                    } catch (Exception $e) {
                        $result['fail'][] = $i;
                    }
                }
                $func = $result['fail'] ? 'rollBack' : 'commit';
                $comment->getResource()->$func();
            } catch (Exception $e) {
                $comment->getResource()->rollBack();
                $result['error'] = $e->getMessage();
            }
        }
        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode($result)
        );
    }
} 