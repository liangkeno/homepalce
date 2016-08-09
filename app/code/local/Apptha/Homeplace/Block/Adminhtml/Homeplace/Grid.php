<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/4/21
 * Time: 11:45
 */
class Apptha_Homeplace_Block_Adminhtml_Homeplace_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        //webGrid ��ֵΪ HTML �����е� <div> ��ǩ�� ID ��ֵ��
        //������ͬһ��ҳ����Ӧ�ö��Grid��ʱ��ID ��ֵ������Ψһ��
        $this->setId('homeplaceGrid');
        //ʹ����һ����ΪĬ������
        $this->setDefaultSort('section_id');
        //Ĭ��Ϊ����
        $this->setDefaultDir('ASC');
        /*      ������������������� Grid ���������Ĳ����� Session �У�
                ����˵���� Grid ���ҳ�еĵ�2ҳ������һЩ����ɸѡ������
                ����ˢ��ҳ��򷵻ص���ҳ��ʱ����������Ĳ�����Ȼ���ڣ�
                ҳ�治�᷵�ص���ʼֵ��״̬*/
        $this->setSaveParametersInSession(true);

        //$this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('homeplace/homeplace')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('section_id', array(
            'header'    => Mage::helper('homeplace')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'section_id',
        ));

        $this->addColumn('section_name', array(
            'header'    => Mage::helper('homeplace')->__('Section name'),
            'align'     =>'left',
            'index'     => 'section_name',
        ));


        $this->addColumn('status', array(
            'header'    => Mage::helper('homeplace')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => 'Enabled',
                2 => 'Disabled',
            ),
        ));

        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('homeplace')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('homeplace')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            ));

       // $this->addExportType('*/*/exportCsv', Mage::helper('homeplace')->__('CSV'));
        //$this->addExportType('*/*/exportXml', Mage::helper('homeplace')->__('XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('section_id');
        $this->getMassactionBlock()->setFormFieldName('homeplace');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('homeplace')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('homeplace')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('homeplace/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('homeplace')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => Mage::helper('homeplace')->__('Status'),
                    'values' => $statuses
                )
            )
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

}