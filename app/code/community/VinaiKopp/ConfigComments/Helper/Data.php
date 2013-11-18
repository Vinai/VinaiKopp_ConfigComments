<?php


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
        
        $this->_mergeComments($comments, $this->loadComments($paths));
        
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
    
    public function loadComments($paths)
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
} 