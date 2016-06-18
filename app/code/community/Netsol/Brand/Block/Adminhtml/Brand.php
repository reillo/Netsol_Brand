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
class Netsol_Brand_Block_Adminhtml_Brand extends Mage_Adminhtml_Block_Widget_Grid_Container{
	
	/**
     * Declaring the construct function
     *
     */
	public function __construct()
	{
		$this->_controller = 'adminhtml_brand';
		$this->_blockGroup = 'brand';
		$this->_headerText = Mage::helper('brand')->__('Item Manager');
		$this->_addButtonLabel = Mage::helper('brand')->__('Add Item');
		parent::__construct();
	}
}
?>