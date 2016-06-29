<?php

class Cis_Supplier_Model_Mysql4_Supplier extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the postalcode_id refers to the key field in your database table.
        $this->_init('supplier/supplier', 'id');
    }
}