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
class Netsol_Brand_BrandController extends Mage_Core_Controller_Front_Action{
	/***
	 * Initalize brand list layout
	 * 
	 * @return Netsol_Brand_IndexController
	 * 
	 * */
    public function indexAction()
    {
		$this->loadLayout();
		$head = $this->getLayout()->getBlock('head');
		if ($head) {
			$brand_metatag = Mage::getStoreConfig('netsol_brand/settings/brand_metatag');
			$brand_description = Mage::getStoreConfig('netsol_brand/settings/brand_description');
			$logowidth = Mage::getStoreConfig('netsol_brand/settings/logo_width');
			$head->setKeywords($brand_metatag);
			$head->setDescription($brand_description);
		}
		$this->renderLayout();
       
    }
    /**
     * Initialize brand view layout
     *
     * * @return  Netsol_Brand_IndexController
     */
    
    public function viewAction(){
		$this->loadLayout();
		
		$this->renderLayout();
    }
    /***
     * Brand list Action
     * 
     * @return all brands alphabetically in json format 
     * */
    public function brandlistAction(){
		$letter = $this->getRequest()->getParam('letter');
		$response = array();
		$collections = Mage::getModel('brand/brand')->getCollection()
						->addFieldToFilter('name', array('like'=>$letter.'%'))
						->setOrder('name', 'ASC');
						
		$response['letter'] = $letter;
		$logowidth = Mage::getStoreConfig('netsol_brand/settings/logo_width');
		$logoheight = Mage::getStoreConfig('netsol_brand/settings/logo_height');
		$defaultlogo = Mage::getStoreConfig('netsol_brand/settings/default_logo');
		$defaultlogo = str_replace("default/","",$defaultlogo);
		
		$defaultlogourl = $this->getLayout()->createBlock('brand/brand')->resize('brand/default/','brand/default/resize/',$defaultlogo,$logowidth,$logoheight); 
		
		$brandurl = Mage::getStoreConfig('netsol_brand/settings/brand_url');
		if($brandurl == '')
		{
			$brandurl = 'brand';
		}
		$brandproducturlsuffix = '.html';
		
		
		foreach($collections as $brand){
			$logourl = $this->getLayout()->createBlock('brand/brand')->resize('brand/logo/','brand/logo/resize/',$brand['logo'],$logowidth,$logoheight); 
			$imageurl = ($brand['logo'] != '') ? $logourl : $defaultlogourl;
			if($imageurl == '')
			{
				$defaultlogo = 'small_image.jpg';
				$imageurl = $this->getLayout()->createBlock('brand/brand')->resize('brand/default/','brand/default/resize/',$defaultlogo,$logowidth,$logoheight);
			}
			
			$response['brand_html'] .= '<li class="allbrand"><div id="brand"><a href="'.$brandurl.'/'.strtolower($brand['name']).trim($brandproducturlsuffix).'"><img class="brand_logo" src="'.$imageurl.'" /><h4 class="brandName">'.$brand['name'].'</h4></a></div></li>';
		}
		
		if(count($collections)== 0){
			$response['brand_html'] = '<h1>No Brands</h1>';
		}
		
		$this->getResponse()->setBody(Mage::Helper('core')->jsonEncode($response));
	}
}
?>
