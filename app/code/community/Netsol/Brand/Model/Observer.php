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
class Netsol_Brand_Model_Observer{ 
   /**Function - adminSaveBrandOptionValue
   ** params :- Varien_Event_Observer $observer
   **purpose :- To save th evalue of attribute brand in brand table
   **/
   public function adminSaveBrandOptionValue(Varien_Event_Observer $observer){
	
		$getSavedAttribute = $observer->getAttribute()->getAttributeCode();
		if($getSavedAttribute == 'brand_name'){
			
		
			//Check if any option is deleted
			$getObserver = $observer->getData();
			$getAttributeArray = $getObserver['attribute']->getOption();
			$optionDeleteArray = $getAttributeArray['delete'];
			//Using in_array we will check if any option is set to delete 
			//and if it is deleted then delete the same from brand table too
			if(in_array("1", $optionDeleteArray)){
				foreach($optionDeleteArray as $key=>$value){
					$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
					$__condition = array($connection->quoteInto('option_id=?', $key));
					$connection->delete('brand', $__condition);
				}
			}

			$attribute_code = "brand_name";
			$attribute_details = Mage::getSingleton("eav/config")->getAttribute("catalog_product", $attribute_code);
			$options = $attribute_details->getSource()->getAllOptions(false);
			$model = Mage::getModel('brand/brand');
			$collection = $model->getCollection()->addFieldToSelect('option_id');
			$optionId = $collection->getData('option_id');
			foreach($optionId as $optionIds){
				$optionIdArray[]= $optionIds['option_id'];
			}
			foreach($options as $option){
				$optionId = $option["value"];
				$label = $option["label"];
				if (!in_array($optionId, $optionIdArray)) {
					$data = array('option_id' => $optionId, 'name' => $label,'status'=>'1');
					 $model->setData($data)->save();
				}
				
			}
		}
    }
}
