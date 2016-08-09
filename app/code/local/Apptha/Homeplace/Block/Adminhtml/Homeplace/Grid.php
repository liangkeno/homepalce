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
        //webGrid 的值为 HTML 代码中的 <div> 标签中 ID 的值，
        //当你在同一个页面中应用多个Grid表时，ID 的值必须是唯一的
        $this->setId('homeplaceGrid');
        //使用那一列作为默认排序
        $this->setDefaultSort('section_id');
        //默认为升序
        $this->setDefaultDir('ASC');
        /*      这个设置用来保存你在 Grid 表中所做的操作到 Session 中，
                比如说你在 Grid 表分页中的第2页或做了一些搜索筛选操作，
                当你刷新页面或返回到该页面时，你刚所做的操作依然存在，
                页面不会返回到初始值或状态*/
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