<?php

class Apptha_Homeplace_Block_Adminhtml_Homeplace_Adedit_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      //设置了表单 id，actoin， method 和 enctype 的值
      $form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                      'method' => 'post',
        							  'enctype' => 'multipart/form-data'
                                   )
      );
      $fieldset = $form->addFieldset('customize_product_form', array('legend'=>Mage::helper('homeplace')->__('customize Products')));

      $fieldset->addField('ad_name', 'text', array(
          'label'     => Mage::helper('homeplace')->__('Advertising Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'ad_name',
      ));


      $form->setUseContainer(true);
      $this->setForm($form);
      return parent::_prepareForm();
  }
}