<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/21
 * Time: 11:26
 */
class Apptha_Homeplace_Block_Adminhtml_Homeplace extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct()
    {
        $this->_controller = 'adminhtml_homeplace';
        $this->_blockGroup = 'homeplace';
        $this->_headerText = Mage::helper('homeplace')->__('Home Section Manager');
        $this->_addButtonLabel = Mage::helper('homeplace')->__('Add Section');
        parent::__construct();
    }
}