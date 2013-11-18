<?php
 
class VinaiKopp_ConfigComments_Model_Resource_Comment extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('vinaikopp_configcomments/comment', 'comment_id');
    }

}