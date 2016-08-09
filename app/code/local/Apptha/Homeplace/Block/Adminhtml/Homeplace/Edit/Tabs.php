<?php

class Apptha_Homeplace_Block_Adminhtml_Homeplace_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('homeplace_tabs');
      //�����ֵӦ�ú����� Form.php �� form id ��ֵһ������id�� => ��edit_form��
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('homeplace')->__('Section Content'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('homeplace')->__('Section Detail'),
          'title'     => Mage::helper('homeplace')->__('Section Detail'),
          'content'   => $this->getLayout()->createBlock('homeplace/adminhtml_homeplace_edit_tab_form')->toHtml(),
      ));
      return parent::_beforeToHtml();
  }
}