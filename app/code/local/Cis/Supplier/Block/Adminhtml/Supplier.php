<?php
class Cis_Supplier_Block_Adminhtml_Supplier extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_supplier';
    $this->_blockGroup = 'supplier';
    $this->_headerText = Mage::helper('supplier')->__("Generate Supplier URL");
    $this->_addButtonLabel = Mage::helper('supplier')->__("Generate Supplier URL");
    parent::__construct();
  }
}