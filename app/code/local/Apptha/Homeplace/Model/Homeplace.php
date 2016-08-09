<?php

class Apptha_Homeplace_Model_Homeplace extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('homeplace/homeplace');
    }
}