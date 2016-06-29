<?php

class Cis_Supplier_Block_Adminhtml_Supplier_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'supplier';
        $this->_controller = 'adminhtml_supplier';
        $this->_updateButton('save', 'label', Mage::helper('supplier')->__('Generate Supplier URL'));
        $this->_updateButton('delete', 'label', Mage::helper('supplier')->__('Delete Supplier URL'));
    }

    public function getHeaderText()
    {
        if( Mage::registry('supplier_data') && Mage::registry('supplier_data')->getId() ) {
            return Mage::helper('supplier')->__("Edit Supplier '%s'", $this->htmlEscape(Mage::registry('supplier_data')->getSupplierName()));
        } else {
            return Mage::helper('supplier')->__('Generate Supplier URL');
        }
    }
}