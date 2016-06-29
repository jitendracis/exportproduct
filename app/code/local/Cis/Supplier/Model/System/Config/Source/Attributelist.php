<?php
class Cis_Supplier_Model_System_Config_Source_Attributelist
{

    public function toOptionArray()
    {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')->addFieldToFilter('frontend_input', array('eq' => 'select'))->getItems();
        
        $option_blank = array(array('value' => '', 'label'=>Mage::helper('adminhtml')->__('--Choose Attribute--')));
        foreach($attributes as $attribute){
            $options[] = array('value' => $attribute->getAttributecode(), 'label'=>Mage::helper('adminhtml')->__($attribute->getFrontendLabel()));
        }
        $options_dropdown = array_merge($option_blank, $options);
        return $options_dropdown;
    }

}
