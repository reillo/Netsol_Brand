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
class Netsol_Brand_Block_Brand extends Mage_Core_Block_Template{
	/***
     * Retrieve attribute code of brand
     * 
     * @return brand attribute code
     * */
    public function getAttributeCode(){
		$code = 'brand_name';
		return $code;
	}
	
	/***
	 * Retrieve form url through helper
	 * 
	 * @return form url
	 * */
	public function getBrandlisturl(){ 
		return Mage::helper('brand')->resultUrl();
	}
	/***
	 * Retrieve brand url through helper
	 * 
	 * @return brand url
	 * */
	public function getBrandurl(){ 
		return Mage::helper('brand')->brandUrl();
	}
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
	/***
	 * Retrieve brand default url through helper
	 * 
	 * @return brand default url
	 * */
	public function getdefaultImage(){
		
		return Mage::Helper('brand')->brandDefaultLogourl();
	}
	/**
	 *  Retrieve brands collections
	 * 
	 * */
	public function getBrands(){

		$collections = Mage::getModel('brand/brand')->getCollection()
						->setOrder('name', 'ASC');
				
		return $collections;
	}
	
	public function resize($source,$destination,$imageName,$width,$height){
		
		return Mage::helper('brand')->resizeImage($source,$destination,$imageName,$width,$height);
	}
	/***
	 * get featured brand in footer
	 * 
	 * @return
	 * */
	 public function getFeaturedbrand(){
		 
		 $collections = Mage::getModel('brand/brand')->getCollection()
						->addFieldToFilter('is_feature',1);
				
		return $collections;
	 }
	
}
