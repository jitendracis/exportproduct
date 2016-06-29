<?php

class Cis_Supplier_Model_Supplierlist extends Mage_Core_Model_Abstract
{
    public function getSupplierList(){
        
        $collection = Mage::getModel('supplier/supplier')->getCollection()
                                                         ->addFieldToSelect('supplier_id');
        $array_inserted = array();
        if(count($collection)){
            foreach($collection as $supplier){
                $array_inserted[] = $supplier['supplier_id'];
            }
        }
        
        $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', Mage::getStoreConfig('supplier/supplier_group/attribute_value')); //"delivery_partner" is the attribute_code
        $allOptions = $attribute->getSource()->getAllOptions(true, true);
        $optionArray = array();
        foreach ($allOptions as $instance) {
            if(!in_array($instance['value'],$array_inserted)){
                $optionArray[] = array("value" => $instance['value'], "label" => Mage::helper('supplier')->__($instance['label']));
            }
        }
        return $optionArray;
    }
}