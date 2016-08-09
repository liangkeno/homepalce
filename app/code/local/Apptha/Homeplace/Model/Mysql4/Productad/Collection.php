<?php

class Apptha_Homeplace_Model_Mysql4_Productad_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('homeplace/productad');
    }
}