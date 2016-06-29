<?php

class Cis_Supplier_Block_Adminhtml_Supplier_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('supplier_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('supplier')->__('Supplier Info'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('supplier')->__('Supplier Info'),
          'title'     => Mage::helper('supplier')->__('Supplier Info'),
          'content'   => $this->getLayout()->createBlock('supplier/adminhtml_supplier_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}