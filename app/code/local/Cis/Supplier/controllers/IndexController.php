<?php
class Cis_Supplier_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
	if (!Mage::getStoreConfig('supplier/supplier_group/enable')) {
		$url = Mage::getBaseUrl();
		Mage::app()->getFrontController()->getResponse()
						 ->setRedirect($url)
						 ->sendResponse();
		exit;
	}
	
	if($this->getRequest()->getParam('filename') == ''){
	    Mage::getSingleton('core/session')->addError(Mage::helper('supplier')->__('Unable to download'));
	    $this->_redirect('*/*/suppliererror');
	    return;
	}
	
	Mage::getSingleton('core/session')->addSuccess(Mage::helper('supplier')->__('Downloaded successfully'));
	$this->loadLayout();     
	$this->renderLayout();
    }
    
    public function suppliererrorAction()
    {
	$this->loadLayout();     
	$this->renderLayout();
    }
    
    public function getstockAction(){
	if (!Mage::getStoreConfig('supplier/supplier_group/enable')) {
	    $url = Mage::getBaseUrl();
	    Mage::app()->getFrontController()->getResponse()
	    ->setRedirect($url)
	    ->sendResponse();
	    exit;
	}
	if ($_GET['key'] && $_GET['key']!='') {
	    $supplier_id = base64_decode(Mage::helper('core')->decrypt($_GET['key']));
	    $supplier_status = Mage::helper('supplier')->checkSupplierStatus($supplier_id);
	    if($supplier_status == 1){
		$product_collection = Mage::helper('supplier')->getSupplierProducts($supplier_id);
		if(count($product_collection) > 0){
		    $excel = Mage::helper('supplier')->generateExcel($product_collection);
		    if($excel){
			$this->_redirect('*/*/index', array('filename' => $excel));
			return;
		    }else{
			Mage::getSingleton('core/session')->addError(Mage::helper('supplier')->__($excel));
			$this->_redirect('*/*/suppliererror');
			return;
		    }
		}elseif(count($product_collection) == 0){
		    Mage::getSingleton('core/session')->addError(Mage::helper('supplier')->__("Products are not available"));
		    $this->_redirect('*/*/suppliererror');
		    return;
		}else{
		    Mage::getSingleton('core/session')->addError(Mage::helper('supplier')->__($product_collection));
		    $this->_redirect('*/*/suppliererror');
		    return;
		}
	    }elseif($supplier_status == 2){
		Mage::getSingleton('core/session')->addError(Mage::helper('supplier')->__("Supplier URL is currently disabled!"));
		$this->_redirect('*/*/suppliererror');
		return;
	    }else{
		Mage::getSingleton('core/session')->addError(Mage::helper('supplier')->__($supplier_status));
		$this->_redirect('*/*/suppliererror');
		return;
	    }
	}else{
	    Mage::getSingleton('core/session')->addError(Mage::helper('supplier')->__('Supplier URL is not correct.'));
	    $this->_redirect('*/*/suppliererror');
	    return;
	}
    } 
}
