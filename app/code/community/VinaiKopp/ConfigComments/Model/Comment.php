<?php

/**
 * @method string getAuthor()
 * @method VinaiKopp_ConfigComments_Model_Comment setAuthor(string $value)
 * @method int getCommentId()
 * @method VinaiKopp_ConfigComments_Model_Comment setCommentId(int $value)
 * @method string getPath()
 * @method VinaiKopp_ConfigComments_Model_Comment setPath(string $value)
 * @method int getSortOrder()
 * @method VinaiKopp_ConfigComments_Model_Comment setSortOrder(int $value)
 * @method string getComment()
 * @method VinaiKopp_ConfigComments_Model_Comment setComment(string $value)
 */
class VinaiKopp_ConfigComments_Model_Comment extends Mage_Core_Model_Abstract
{
    protected $_eventPrefix = 'vinaikopp_configcomment';
    protected $_eventObject = 'comment';
    
    protected function _construct()
    {
        $this->_init('vinaikopp_configcomments/comment');
    }
}