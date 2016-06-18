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
class Netsol_Brand_Block_Adminhtml_Brand_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{

	public function __construct(){
		parent::__construct();
			   
		$this->_objectId = 'id';
		$this->_blockGroup = 'brand';
		$this->_controller = 'adminhtml_brand';
		$this->_updateButton('save', 'label', Mage::helper('brand')->__('Save Brand'));
		$this->_updateButton('delete', 'label', Mage::helper('brand')->__('Delete Brand'));
	}
 
	public function getHeaderText(){
		if( Mage::registry('brand_data') && Mage::registry('brand_data')->getId() ) {
			return Mage::helper('brand')->__("Edit Brand '%s'", $this->htmlEscape(Mage::registry('brand_data')->getName()));
		} else {
			return Mage::helper('brand')->__('Add Brand');
		}
	}
}