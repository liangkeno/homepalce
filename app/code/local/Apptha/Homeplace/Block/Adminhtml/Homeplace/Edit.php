<?php

class Apptha_Homeplace_Block_Adminhtml_Homeplace_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        //���������Ӧ���ڱ��� URL �У� �������˱�ʵ�������
        //��ɾ����ť�� URL ��˵: ģ��/������/������/$this->_objectid/3
        $this->_objectId = 'id';

        //�����������Ƚ���Ҫ�����Ǿ�������������ѡ���tabs���ļ��ģ�
        //��·��Ӧ���� {$this->_blockGroup . '/' . $this->_controller . '_' . $this->_mode . '_form'}��
        //���е�$this->mode����Ĭ��ֵ: 'edit'�� ��ôչ�ֳ���·����: 'employee/adminhtml_employee_edit_form'
        $this->_blockGroup = 'homeplace';
        $this->_controller = 'adminhtml_homeplace';

        //�������ڱ���������ӻ���°�ť�ģ� Ч��������ͼ�ĺ����չʾ
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