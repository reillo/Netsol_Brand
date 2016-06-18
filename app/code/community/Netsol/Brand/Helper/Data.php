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
class Netsol_Brand_Helper_Data extends Mage_Core_Helper_Abstract{
	/***
	 * retrieve the brand list url 
	 * 
	 * @return url
	 * 
	 ****/
    public function resultUrl(){ 
		
 		return Mage::getUrl('brand/brand/brandlist');
	}
	
	/***
	 * retrieve the brand list url 
	 * 
	 * @return url
	 * 
	 ****/
    public function brandUrl(){ 
		
 		return Mage::getUrl('brand');
	}
	/***
	 * retrieve the brand base_image
	 * 
	 * @return url
	 * **/
	  public function brandBaseImageUrl(){
		  
		 $baseimageurl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'brand/';
		
 		return $baseimageurl;
	}
	
	/***
	 * retrieve the brand logo image
	 * 
	 * @return url
	 * **/
	  public function brandLogoImageUrl(){
		  
		 $logoimageurl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'brand/logo/';
		
 		return $logoimageurl;
	}
	/***
	 * retrieve the brand default image
	 * 
	 * @return $default logo 
	 * */
	 public function brandDefaultLogourl(){
		 
		$baseimageurl = $this->brandBaseImageUrl();
		$defaultlogo = $baseimageurl.Mage::getStoreConfig('netsol_brand/settings/default_logo'); 
		
		return $defaultlogo;
	 }
	 
	 public function resizeImage($source,$destination,$imageName,$width,$height){
		
	  if (!file_exists("'./media/".$source."'resize")){
		mkdir("'./media/".$source."'resize", 0777);
	  }
	  $sourceImage = Mage::getBaseDir('media').DS.$source.$imageName; 
	  
	  if($imageName != ''){
		 if($height){
			$imageName = $height."_".$imageName;
		 }
		 if($width){
			$imageName = $width."_".$imageName;
		 }
		   // path of the resized image to be saved
		 $imageResized = Mage::getBaseDir('media').DS.$destination.$imageName;
		 if (!file_exists($imageResized)&&file_exists($sourceImage)) {
			  $imageObj = new Varien_Image($sourceImage);
			  $imageObj->constrainOnly(TRUE);
			  $imageObj->keepAspectRatio(TRUE);
			  $imageObj->keepFrame(TRUE);
			  $imageObj->backgroundColor(array(255, 255, 255));
			  $imageObj->resize($width, $height);
			  $imageObj->save($imageResized);
		 }
		 $newImageUrl = Mage::getBaseUrl('media').$destination.$imageName;
	  }else{
			$newImageUrl = "";
	  }
	  return $newImageUrl;
	}
	/**
	 * retrieve the brand enabled status
	 * */
	public function showTemplate()
	{
		$moduleenabled = Mage::getStoreConfig('netsol_brand/settings/status');
		if(Mage::app()->getLayout()->createBlock('page/html_topmenu')){
			
			if ($moduleenabled) { 
				Mage::app()->getLayout()->getBlock('top.menu')->unsetBlock('top.menu');
				Mage::app()->getLayout()->getBlock('top.menu')->unsetChild('catalog.topnav');
				return 'brand/navigation/brandtopmenu.phtml';
			} else {  
				return 'page/html/topmenu.phtml';
			}
			
		}else{  
			if ($moduleenabled) { 
				Mage::app()->getLayout()->getBlock('top.menu')->unsetBlock('top.menu');
				Mage::app()->getLayout()->getBlock('top.menu')->unsetChild('catalog.topnav'); 
				return 'brand/navigation/brandtopmenu.phtml';
			} else { 
				Mage::app()->getLayout()->getBlock('top.menu')->unsetBlock('top.menu');
				Mage::app()->getLayout()->getBlock('top.menu')->unsetChild('catalog.topnav'); 
				return 'catalog/navigation/top.phtml';
			}
		}
	}
	/**
	 * @return the featured brand when module and show in footer is enabled 
	 * */
	public function showFeaturedbrand()
	{
		$moduleenabled = Mage::getStoreConfig('netsol_brand/settings/status');
		$showInfooter = Mage::getStoreConfig('netsol_brand/settings/show_in_footer');
		
		if($moduleenabled && $showInfooter)
		{
			return 'brand/brandfooter.phtml';
		}
	}
	
}
