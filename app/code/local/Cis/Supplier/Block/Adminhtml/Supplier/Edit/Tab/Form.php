<?php

class Cis_Supplier_Block_Adminhtml_Supplier_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('supplier_form', array('legend'=>Mage::helper('supplier')->__('Supplier Info')));
      
      $fieldset->addField('supplier_id', 'select', array(
            'name'      => 'supplier_id',
            'label'     => Mage::helper('supplier')->__('Supplier Name'),
            'title'     => Mage::helper('supplier')->__('Supplier Name'),
	    'values'    => Mage::getModel('supplier/supplierlist')->getSupplierList(),
	    'required'  => true,
      ));
      
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('supplier')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('supplier')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('supplier')->__('Disabled'),
              ),
          ),
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getSupplierData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getSupplierData());
          Mage::getSingleton('adminhtml/session')->setSupplierData(null);
      } elseif ( Mage::registry('supplier_data') ) {
          $form->setValues(Mage::registry('supplier_data')->getData());
      }
      return parent::_prepareForm();
  }
}