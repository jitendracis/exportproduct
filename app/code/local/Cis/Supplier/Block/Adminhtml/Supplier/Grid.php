<?php

class Cis_Supplier_Block_Adminhtml_Supplier_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('supplierGrid');
      $this->setDefaultSort('id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('supplier/supplier')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
    
      $this->addColumn('id', array(
          'header'    => Mage::helper('supplier')->__('S.no'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'id',
      ));

      $this->addColumn('supplier_id', array(
          'header'    => Mage::helper('supplier')->__('Supplier Admin ID'),
          'align'     =>'left',
          'index'     => 'supplier_id',
      ));
      
      $this->addColumn('supplier_name', array(
          'header'    => Mage::helper('supplier')->__('Supplier Name'),
          'align'     =>'left',
          'index'     => 'supplier_name',
      ));
      
      $this->addColumn('supplier_url', array(
          'header'    => Mage::helper('supplier')->__('Supplier URL'),
          'align'     =>'left',
          'index'     => 'supplier_url',
      ));
      
      $this->addColumn('status', array(
          'header'    => Mage::helper('supplier')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
		
      $this->addExportType('*/*/exportCsv', Mage::helper('supplier')->__('CSV'));
      $this->addExportType('*/*/exportXml', Mage::helper('supplier')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
      
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('supplier');
	
	

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('supplier')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('supplier')->__('Are you sure?')
        ));

        $statuses = Mage::getModel('supplier/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('supplier')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('supplier')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
    // return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}