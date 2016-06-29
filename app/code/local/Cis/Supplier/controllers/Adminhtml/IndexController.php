<?php

class Cis_Supplier_Adminhtml_IndexController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
		     ->_setActiveMenu('supplier/items')
		     ->_addBreadcrumb(Mage::helper('adminhtml')->__("Supplier URL's"), Mage::helper('adminhtml')->__("Supplier URL's"));
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
		     ->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('supplier/supplier')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('supplier_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('supplier/items');

			$this->_addBreadcrumb(Mage::helper('supplier')->__("Supplier URL's"), Mage::helper('adminhtml')->__("Supplier URL's"));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('supplier/adminhtml_supplier_edit'))
				->_addLeft($this->getLayout()->createBlock('supplier/adminhtml_supplier_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('supplier')->__("Supplier does not exist"));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			$attrObj = Mage::getModel('catalog/product')->getResource()->getAttribute(Mage::getStoreConfig('supplier/supplier_group/attribute_value'));
			$data['supplier_name'] = $attrObj->getSource()->getOptionText($data['supplier_id']);
			$supplier_id = $data['supplier_id'];
			$encrypted_data = Mage::helper('core')->encrypt(base64_encode($supplier_id));
			$data['supplier_url'] = Mage::getBaseUrl()."supplier/index/getstock?key=".$encrypted_data;

			$model = Mage::getModel('supplier/supplier');		
			$model->setData($data)
			      ->setId($this->getRequest()->getParam('id'));
			try {
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('supplier')->__('Supplier URL saved successfully'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('supplier')->__('Unable to find Supplier to save'));
        $this->_redirect('*/*/');
	}
 
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('supplier/supplier');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('supplier')->__('Supplier URL deleted successfully'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $supplierIds = $this->getRequest()->getParam('supplier');
        if(!is_array($supplierIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('supplier')->__('Please select supplier(s)'));
        } else {
            try {
                foreach ($supplierIds as $supplierId) {
                    $supplier = Mage::getModel('supplier/supplier')->load($supplierId);
                    $supplier->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($supplierIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $supplierIds = $this->getRequest()->getParam('supplier');
        if(!is_array($supplierIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select supplier(s)'));
        } else {
            try {
                foreach ($supplierIds as $supplierId) {
                    $supplier = Mage::getSingleton('supplier/supplier')
                        ->load($supplierId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($supplierIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'supplier.csv';
        $content    = $this->getLayout()->createBlock('supplier/adminhtml_supplier_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'supplier.xml';
        $content    = $this->getLayout()->createBlock('supplier/adminhtml_supplier_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }

}