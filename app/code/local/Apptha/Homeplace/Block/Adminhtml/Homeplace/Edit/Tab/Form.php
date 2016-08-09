<?php

class Apptha_Homeplace_Block_Adminhtml_Homeplace_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);

      //Section 标题
      $fieldset = $form->addFieldset('section_title', array('legend'=>Mage::helper('homeplace')->__('Section Name')));
      $fieldset->addField('section_name', 'text', array(
          'label'     => Mage::helper('homeplace')->__('Section name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'section_name',
      ));
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('homeplace')->__('Section Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('homeplace')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('homeplace')->__('Disabled'),
              ),
          ),
      ));

      //Section广告活动
      $fieldset = $form->addFieldset('section_adverting', array('legend'=>Mage::helper('homeplace')->__('Section Adverting')));
      $fieldset->addField('ad_activity_brief', 'textarea', array(
          'label'               =>  Mage::helper('homeplace')->__('Adverting Brief'),
          'class'               =>  'required-entry',
          'required'            =>  true,
          'name'                =>  'ad_activity_brief',
          'onclick'             =>  "",
          'onchange'            =>  "",
          'value'               =>  '<b></b>',
          'after_element_html'  =>  '<p>在激活类目(Section Category)的时候不显示</p>',
          'disabled'            =>  false,
          'tabindex'            =>  1
      ));

      $fieldset->addField('ad_activity_link', 'text', array(
          'label'     => Mage::helper('homeplace')->__('Adverting link'),
          'required'  => false,
          'name'      => 'ad_activity_link',
      ));

      $fieldset->addField('ad_activity_path', 'image', array(
          'label'     => Mage::helper('homeplace')->__('Adverting Image URL'),
          'required'  => false,
          'name'      => 'ad_activity_path',
          'after_element_html'  =>  '<p>图片尺寸：385*300</p>',
	  ));


      //Section类目
      $fieldset = $form->addFieldset('cate_form', array('legend'=>Mage::helper('homeplace')->__('Section Category')));
      $fieldset->addField('cate_select', 'text', array(
          'label'     => Mage::helper('homeplace')->__('Category ID'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'cate_select',
      ));
      $fieldset->addField('cate_status', 'select', array(
          'label'     => Mage::helper('homeplace')->__('Status'),
          'name'      => 'cate_status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('homeplace')->__('Enabled'),
              ),

              array(
                  'value'     => 0,
                  'label'     => Mage::helper('homeplace')->__('Disabled'),
              ),
          ),
      ));
      //Section 4 个产品
      $fieldset = $form->addFieldset('fourproduct_form', array('legend'=>Mage::helper('homeplace')->__('Section four product')));
      $fieldset->addField('product_list_status', 'multiselect', array(
          'label'               =>  Mage::helper('homeplace')->__('Select Status'),
          'class'               =>  'required-entry',
          'required'            =>  true,
          'name'                =>  'product_list_status',
          'onclick'             =>  "customize(this.value)",
          'onchange'            =>  "return false;",
          'value'               =>  '4',
          'values'              =>  array(
              '1' => array(
                  'value' => array(
                      array(
                          'value'  => '1',
                          'label'  => 'latest Products'
                      ),
                      array(
                          'value'  => '2',
                          'label'  => 'Top sale Products'
                      ),
                      array(
                          'value'  => '3',
                          'label'  => 'customize Products'
                      )
                  ),
                  'label' => 'Select '
              ),
          ),
          'disabled'            =>  false,
          'readonly'            =>  false,
          'after_element_html'  =>  '<small>Comments</small>',
          'tabindex'            =>  1
      ));
      $fieldset->addField('four_products', 'text', array(
          'label'     => Mage::helper('homeplace')->__('Customize product ID'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'four_products',
      ));


    //Section广告slider
      $fieldset = $form->addFieldset('customize_product_form', array('legend'=>Mage::helper('homeplace')->__('Section Slider')));
      $fieldset->addField('ad_list', 'text', array(
          'label'     => Mage::helper('homeplace')->__('Slider Adverting ID'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'ad_list',
      ));
      $fieldset->addField('ad_style', 'text', array(
          'label'     => Mage::helper('homeplace')->__('Slider backGround color'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'ad_style',
      ));

      $fieldset->addField('authorize_btn', 'button', array(
          'name' => 'authorize_btn',
          'label' => Mage::helper('homeplace')->__(
              'Click to add Advertising'
          ),
          'value' => $this->helper('homeplace')->__('Test popup dialog >>'),
          'class' => 'form-button',
          'onclick' => 'javascript:openMyPopup()'
      ));


      if ( Mage::getSingleton('adminhtml/session')->getWebData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getWebData());
          Mage::getSingleton('adminhtml/session')->setWebData(null);
      } elseif ( Mage::registry('homeplace_data') ) {
          //使用控制器edit 方法  注册的数据 Mage::register('homeplace_data', $model);
          $form->setValues(Mage::registry('homeplace_data')->getData());
      }
      return parent::_prepareForm();
  }
}