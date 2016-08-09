<?php

class Apptha_Homeplace_Model_Mysql4_Productad extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the homeplace_id refers to the key field in your database table.
        $this->_init('homeplace/productad', 'product_ad_id');
    }
}