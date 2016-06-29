<?php

class Cis_Supplier_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function checkSupplierStatus($supplier_id){
        
        $collection = Mage::getModel('supplier/supplier')->getCollection()
                                                         ->addFieldToFilter('supplier_id', array('eq' => $supplier_id))
                                                         ->addFieldToSelect(array('status'));
        try{
	    $data = $collection->getData();
	    return $data[0]['status'];
	}catch(Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function getSupplierProducts($supplier_id){
        try{
            if(Mage::getStoreConfig('supplier/supplier_group/attribute_value') != ''){
            $collection = Mage::getModel('catalog/product')->getCollection()
                            ->addAttributeToSelect(array('name','sku'))
                            ->addFieldToFilter(Mage::getStoreConfig('supplier/supplier_group/attribute_value'), array('eq' => $supplier_id))
                            ->joinField('qty', 'cataloginventory/stock_item', 'qty', 'product_id=entity_id', '{{table}}.stock_id=1', 'left')
                            ->addAttributeToFilter(
                                 'qty', 
                                 array("gteq" => 0)
                             );
            return $collection;
            }else{
                return;
            }
        }catch(Exception $e) {
            return $e->getMessage();
        }  
    }
    
    public function generateExcel($product_collection){
        $xlswriter_class_path = Mage::getBaseDir().'/lib/cis/xlsw/xlsxwriter.class.php';
        require_once($xlswriter_class_path);
        $file_download = $this->createFileFromResult($product_collection);
        try{
            return $file_download;
        }catch(Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function createFileFromResult($product_collection)
    {
        $header = array(
            "Name"        => "string",
            "SKU"         => "string",
            "Stock"       => "string"
        );
        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header);//optional
        $resource       = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
    
        foreach ($product_collection as $product) {
            $writer->writeSheetRow('Sheet1', array(
                $product->getName(),
                $product->getSku(),
                $product->getQty()
            ));
        }
        $date = date('mdYhis').rand(0,99999);
        $baseDirectory = Mage::getBaseDir().'/excel';
        if(!is_dir($baseDirectory)){
                mkdir($baseDirectory, 0777, true);
                $this->createHtaccess($baseDirectory);
        }
        $excelfile_path = $baseDirectory.'/productExcel' . $date . '.xlsx';
        $writer->writeToFile($excelfile_path);
        chmod($excelfile_path);
        return "productExcel".$date.".xlsx";
    }
    
    public function createHtaccess($baseDirectory){
        $fileLocation = $baseDirectory."/.htaccess";
        $file = fopen($fileLocation,"w");
        $content = 'Order Allow,Deny
        Deny from all
        <FilesMatch "\.(xlsx)$">
        Order Deny,Allow
        Allow from all
        </FilesMatch>';
        fwrite($file,$content);
        fclose($file);
    }
    
}