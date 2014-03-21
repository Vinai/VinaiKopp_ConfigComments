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