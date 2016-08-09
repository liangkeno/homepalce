<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/25
 * Time: 10:21
 */

class Apptha_Homeplace_Block_Adminhtml_Adverting extends  Mage_Adminhtml_Block_Widget_Grid {

    public function getAdData($adId){
        $collection = Mage::getModel('homeplace/productad')->load($adId);
        return $collection;
    }

}