<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Netsol
 * @package     Netsol_Brand
 * @copyright   Copyright (c) 2015 Netsolutions India (http://www.netsolutions.in)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/     
class Netsol_Brand_Adminhtml_BrandController extends Mage_Adminhtml_Controller_Action
{
 
	protected function _initAction()
	{
		$this->loadLayout()
			->_setActiveMenu('brand/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Brands Manager'));
		return $this;
	}   
   
	public function indexAction() {
		$this->_initAction();
		$this->_addContent($this->getLayout()->createBlock('brand/adminhtml_brand'));
		$this->renderLayout();
	}
 
	public function editAction()
	{
		$brandId     = $this->getRequest()->getParam('id');
		$brandModel  = Mage::getModel('brand/brand')->load($brandId);
		if ($delete = $this->getRequest()->getParam('delete')){
		    switch ($delete){
		        case 'logo':
		        case 'base_image':
		            $params = $this->getRequest()->getParams();
		            unset($params['delete']);
		            if ($brandModel->getFeatured() && 'base_image' == $delete){
		                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brand')->__('Image must be uploaded for Featured Brand'));
                        Mage::getSingleton('adminhtml/session')->setFormData($brandModel->getData());
                        $this->_redirect('*/*/*/', $params);
                        return;
		            }
		            $filename = $brandModel->getData($delete);
		            $path = Mage::getBaseDir('media') . DS . 'brand' . DS . ($delete == 'logo'?'logo'.DS:'');
		            if (file_exists($path.$filename)){
		                unlink($path.$filename);
		            }
		            $brandModel->setData($delete, '');
		            $brandModel->save();
		            $this->_redirect('*/*/*/', $params);
		            return;
		    }
		}
		if ($brandModel->getId() || $brandId == 0) {
 
			Mage::register('brand_data', $brandModel);
 
			$this->loadLayout();
			$this->_setActiveMenu('brand/items');
		   
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Brands'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
		   
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
		   
			$this->_addContent($this->getLayout()->createBlock('brand/adminhtml_brand_edit'))
				 ->_addLeft($this->getLayout()->createBlock('brand/adminhtml_brand_edit_tabs'));
			   
			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('brand')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
   
		public function newAction()
		{
			$this->_forward('edit');
		}
   
		public function saveAction(){
			if ( $this->getRequest()->getPost() ) {
				try {
					$postData = $this->getRequest()->getPost();
					$brandModel = Mage::getModel('brand/brand');
				   if (isset($_FILES['base_image']['name']) && $_FILES['base_image']['name'] != '') {
					try {
						/* Starting upload */	
						$uploader = new Varien_File_Uploader('base_image');
						// Any extention would work
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
						$uploader->setAllowRenameFiles(false);
						$uploader->setFilesDispersion(false);
						// We set media as the upload dir
						$path = Mage::getBaseDir('media') . DS . 'brand' . DS;
						//$uploader->save($path, $_FILES['image']['name'] );
						$imagename = md5($_FILES['base_image']['name'].time()) . '.' . substr(strrchr($_FILES['base_image']['name'], '.'), 1);
						$uploader->save($path, $imagename);
					} catch (Exception $e) {
						echo $e->getMessage();
					}
					if (isset($imagename)){
						//this way the name is saved in DB
						$postData['base_image'] = $imagename;
					}
				}
				if (isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != '') {
					try {	
						$uploader = new Varien_File_Uploader('logo');
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
						$uploader->setAllowRenameFiles(false);
						$uploader->setFilesDispersion(false);
						$path = Mage::getBaseDir('media') . DS . 'brand' . DS . 'logo' . DS;
						$logoName = md5($_FILES['logo']['name'].time()) . '.' . substr(strrchr($_FILES['logo']['name'], '.'), 1);
						$uploader->save($path, $logoName);
					} catch (Exception $e) {
					}
					if (isset($logoName)){
						//this way the name is saved in DB
						$postData['logo'] = $logoName;
					}
				}
				//Save attribute if new brand is added
				if($this->getRequest()->getParam('id') == ''){
					  $arg_attribute = 'brand_name';
					  $arg_value = $postData['name'];

					  $attr_model = Mage::getModel('catalog/resource_eav_attribute');
					  $attr = $attr_model->loadByCode('catalog_product', $arg_attribute);
					  $attr_id = $attr->getAttributeId();

					  $option['attribute_id'] = $attr_id;
					  $option['value']['any_option_name'][0] = $arg_value;

					  $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
					//Get the last attribute id and get the option id.
					  $setup->addAttributeOption($option);
					  $db_write = Mage::getSingleton('core/resource')->getConnection('core_read');
					  $lastId = $db_write ->lastInsertId();
					  $select = $db_write->select()->from('eav_attribute_option_value', array('option_id'))->where('value_id=?',$lastId);
					  $rowArray =$db_write->fetchRow($select);
					  $postData['option_id'] =  $rowArray['option_id'];
				}

				$brandModel->setId($this->getRequest()->getParam('id'))
					->setName($postData['name'])
					->setDescription($postData['description'])
					->setStatus($postData['status'])
					->setIsFeature($postData['is_feature'])
					->setBaseImage($postData['base_image'])
					->setOptionId($postData['option_id'])
					->setLogo($postData['logo'])
					->save();
				   
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Brand was successfully saved'));
					Mage::getSingleton('adminhtml/session')->setBrandData(false);
	 
					$this->_redirect('*/*/');
					return;
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					Mage::getSingleton('adminhtml/session')->setBrandData($this->getRequest()->getPost());
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
					return;
				}
			}
			$this->_redirect('*/*/');
		}
   
		public function deleteAction(){
			if( $this->getRequest()->getParam('id') > 0 ) {
				try {
					$brandModel = Mage::getModel('brand/brand');
				
					/*Load brand with the help of brand id and delete the option too with respect to the attribute*/
					$brandId = $this->getRequest()->getParam('id');
					$brand = Mage::getModel('brand/brand')->load($brandId);
					$arg_attribute = "brand_name";
					$option_id = $brand->getOptionId();//Getting the option id(attribute option)
					$attr_model = Mage::getModel('catalog/resource_eav_attribute');
					$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product',$arg_attribute);
					$options = $attribute->getSource()->getAllOptions();
					$options['delete'][$option_id] = true; 
					$options['value'][$option_id] = true;

					$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
					//Removing the option id.
					$setup->addAttributeOption($options);

					$brandModel->setId($this->getRequest()->getParam('id'))
						->delete();
					   
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Brand was successfully deleted'));
					$this->_redirect('*/*/');
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				}
			}
			$this->_redirect('*/*/');
		}

		/**
		* Product grid for AJAX request.
		* Sort and filter result for example.
		*/
		public function gridAction(){
			$this->loadLayout();
			$this->getResponse()->setBody($this->getLayout()->createBlock('brand/adminhtml_brand_grid')->toHtml());
		}

		public function massStatusAction(){
			$this->_initAction();
			$brandIds = $this->getRequest()->getParam('brand');
			if(!is_array($brandIds)) {
				Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Brands Page(s)'));
			} else {
				try {
					foreach ($brandIds as $brandId) {
						$brand = Mage::getSingleton('brand/brand')
							->load($brandId)
							->setStatus($this->getRequest()->getParam('status'))
							->setIsMassupdate(true)
							->save();
					}
					$this->_getSession()->addSuccess(
						$this->__('Total of %d record(s) were successfully updated', count($brandIds)));
				} catch (Exception $e) {
					$this->_getSession()->addError($e->getMessage());
				}
			}
			$this->_redirect('*/*/index');
		}

		public function massDeleteAction(){
			$this->_initAction();
			$brandIds = $this->getRequest()->getParam('brand');
			if(!is_array($brandIds)) {
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Brands Page(s)'));
			} else {
				try {
					foreach ($brandIds as $brandId) {
                    $brand = Mage::getModel('brand/brand')->load($brandId);
					/*Delete the attribute option too*/
					$arg_attribute = "brand_name";
					$option_id = $brand->getOptionId();//Getting the option id(attribute option)
					$attr_model = Mage::getModel('catalog/resource_eav_attribute');
					$attribute = Mage::getModel('eav/config')->getAttribute('catalog_product',$arg_attribute);
					$options = $attribute->getSource()->getAllOptions();
					$options['delete'][$option_id] = true; 
					$options['value'][$option_id] = true;

					$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
					//Removing the option id.
					$setup->addAttributeOption($options);
                    $brand->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($brandIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
		 }
        $this->_redirect('*/*/index');
	 }
}