<?php

class Apptha_Homeplace_Block_Adminhtml_Homeplace_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        //这个变量被应用于表单的 URL 中， 它包含了表单实体的主键
        //拿删除按钮的 URL 来说: 模块/控制器/方法名/$this->_objectid/3
        $this->_objectId = 'id';

        //这两个变量比较重要，它们就是用来锁定表单选项卡（tabs）文件的，
        //其路径应该是 {$this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form'}，
        //当中的$this->mode含有默认值: 'edit'， 那么展现出的路径是: 'employee/adminhtml_employee_edit_form'
        $this->_blockGroup = 'homeplace';
        $this->_controller = 'adminhtml_homeplace';

        //是用来在表单容器中添加或更新按钮的， 效果已在上图的红框中展示
        $this->_updateButton('save', 'label', Mage::helper('homeplace')->__('Save Section'));
        $this->_updateButton('delete', 'label', Mage::helper('homeplace')->__('Delete Section'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function customize(value){
                var customizeNode=document.getElementById('four_products');
                if(value == 3){
                    var attr= customizeNode.getAttributeNode('disabled');
                    customizeNode.removeAttributeNode(attr);
                }else{
                    customizeNode.setAttribute('disabled','ture');
                }
            }
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('homeplace_data') && Mage::registry('homeplace_data')->getId() ) {
            return Mage::helper('homeplace')->__("Edit Section '%s'", $this->htmlEscape(Mage::registry('homeplace_data')->getSectionName()));
        } else {
            return Mage::helper('homeplace')->__('Add Section');
        }
    }
}