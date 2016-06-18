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
class Netsol_Brand_Block_Brandproductlist extends Mage_Catalog_Block_Product_List{ 
	/***
	 * Retrieve brand url through helper
	 * 
	 * @return brand url
	 * */
	public function getBrandimageurl(){
		return Mage::helper('brand')->brandBaseImageUrl();
	}
	/***
	 * Retrieve brandlogo url through helper
	 * 
	 * @return brandlogo url
	 * */
	public function getBrandlogoimageurl(){
		return Mage::helper('brand')->brandLogoImageUrl();
	}
	/****
	* 
	* @return brand product lists
	* */
	public function getBrandproductcollection() {
		
		$dir = $this->getRequest()->getParam('dir');
		$order = $this->getRequest()->getParam('order');
		$limit = $this->getRequest()->getParam('limit');
		$page = $this->getRequest()->getParam('p');
		$pagerBlock = $this->getChild('brandtoolbar');
		$dlimit =$pagerBlock->getLimit();
		$dorder = $pagerBlock->getCurrentOrder();
		$ddir = $pagerBlock->getCurrentDirection();
		$currentUrl = $this->helper('core/url')->getCurrentUrl(false);
		$url = Mage::getSingleton('core/url')->parseUrl($currentUrl);
		$path = $url->getPath();
		$path = urldecode($path);
		$brandproducturlsuffix = '.html';
		$brand_name = basename($path,$brandproducturlsuffix );
		//$brand_name = htmlentities($brand_name);
		$collections = Mage::getModel('brand/brand')->getCollection()
						->addFieldToSelect('brand_id')
						->addFieldToSelect('option_id')
						->addFieldToFilter('name', $brand_name);
		$brand = $collections->getFirstItem();

		$sAttributeName = 'brand_name';
		
		if($dir && $order){ 
			$collection = Mage::getModel('catalog/product')->getCollection()
						->addAttributeToSelect('*')
						->addAttributeToFilter(
							'status',
							array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED) 
								//replace DISABLED to ENABLED for products with status enabled
						 )
						->addFieldToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
						->addWebsiteFilter()
						->addAttributeToFilter($sAttributeName, array('eq' => $brand->getOptionId()))
						->setOrder($order, $dir)
						->setPageSize($dlimit);
			 Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
		}elseif($limit){
			$collection = Mage::getModel('catalog/product')->getCollection()
						->addAttributeToSelect('*')
						->addAttributeToFilter(
							'status',
							array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED) 
								//replace DISABLED to ENABLED for products with status enabled
						 )
						->addFieldToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
						->addWebsiteFilter()
						->addAttributeToFilter($sAttributeName, array('eq' => $brand->getOptionId()))
						->setOrder($order, $dir)
						->setPageSize($limit);
			 Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
		}elseif($page){
			$collection = Mage::getModel('catalog/product')->getCollection()
						->addAttributeToSelect('*')
						->addAttributeToFilter(
							'status',
							array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED) 
								//replace DISABLED to ENABLED for products with status enabled
						 )
						->addFieldToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
						->addWebsiteFilter()
						->addAttributeToFilter($sAttributeName, array('eq' => $brand->getOptionId()))
						->setOrder($dorder, $ddir)
						->setPageSize($dlimit)
						->setCurPage($page);
			 Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
	    }else{
			$collection = Mage::getModel('catalog/product')->getCollection()
						->addAttributeToSelect('*')
						->addAttributeToFilter(
							'status',
							array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED) 
								//replace DISABLED to ENABLED for products with status enabled
						 )
						->addFieldToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
						->addWebsiteFilter()
						->addAttributeToFilter($sAttributeName, array('eq' => $brand->getOptionId()))
						->setOrder($dorder, $ddir)
						->setPageSize($dlimit);
						//->setCurPage($page);
			 Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
		}

		return $collection;
	}
 	/****
	* 
	* @return brand info details
	* */
	public function getBranddetails(){
		$currentUrl = $this->helper('core/url')->getCurrentUrl(false);
		$url = Mage::getSingleton('core/url')->parseUrl($currentUrl);
		$path = $url->getPath();
		
		$brandproducturlsuffix = '.html';
		$brand_name = basename($path,$brandproducturlsuffix );
		$brand_info = Mage::getModel('brand/brand')->getCollection()
						->addFieldToFilter('name', $brand_name);
						
		$brand_info = $brand_info->getFirstItem();
		
		return $brand_info;
	}
	
	public function resize($source,$destination,$imageName,$width,$height){
		
		return Mage::helper('brand')->resizeImage($source,$destination,$imageName,$width,$height);
	}
	
	 /**
     * Retrieve current view mode
     *
     * @return string
     */
    public function getMode(){
		
        return $this->getChild('brandtoolbar')->getCurrentMode();
        
    }

	
}
