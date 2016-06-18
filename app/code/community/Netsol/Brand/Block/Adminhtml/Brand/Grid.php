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
class Netsol_Brand_Block_Adminhtml_Brand_Grid extends Mage_Adminhtml_Block_Widget_Grid{
	
        public function __construct()
        {
            parent::__construct();
            $this->setId('brandGrid');
            // This is the primary key of the database
            $this->setDefaultSort('brand_id');
            $this->setDefaultDir('ASC');
            $this->setSaveParametersInSession(true);
            $this->setUseAjax(true);
        }
     
        protected function _prepareCollection()
        {
            $collection = Mage::getModel('brand/brand')->getCollection();
            $this->setCollection($collection);
            return parent::_prepareCollection();
        }
     
        protected function _prepareColumns()
        {
            $this->addColumn('brand_id', array(
                'header'    => Mage::helper('brand')->__('ID'),
                'align'     =>'right',
                'width'     => '50px',
                'index'     => 'brand_id',
            ));
     
            $this->addColumn('name', array(
                'header'    => Mage::helper('brand')->__('Name'),
                'align'     =>'left',
                'index'     => 'name',
            ));
     
            /*
            $this->addColumn('content', array(
                'header'    => Mage::helper('brand')->__('Item Content'),
                'width'     => '150px',
                'index'     => 'content',
            ));
            */
     
            $this->addColumn('created_time', array(
                'header'    => Mage::helper('brand')->__('Creation Time'),
                'align'     => 'left',
                'width'     => '120px',
                'type'      => 'date',
                'default'   => '--',
                'index'     => 'created_time',
            ));
     
            $this->addColumn('update_time', array(
                'header'    => Mage::helper('brand')->__('Update Time'),
                'align'     => 'left',
                'width'     => '120px',
                'type'      => 'date',
                'default'   => '--',
                'index'     => 'update_time',
            ));   
			
			$this->addColumn('is_feature', array(
     
                'header'    => Mage::helper('brand')->__('Is Feature'),
                'align'     => 'left',
                'width'     => '80px',
                'index'     => 'is_feature',
                'type'      => 'options',
                'options'   => array(
                    1 => 'Yes',
                    0 => 'No',
                ),
            ));
     
            $this->addColumn('status', array(
     
                'header'    => Mage::helper('brand')->__('Status'),
                'align'     => 'left',
                'width'     => '80px',
                'index'     => 'status',
                'type'      => 'options',
                'options'   => array(
                    1 => 'Active',
                    0 => 'Inactive',
                ),
            ));
     
            return parent::_prepareColumns();
        }
     
        public function getRowUrl($row)
        {
            return $this->getUrl('*/*/edit', array('id' => $row->getId()));
        }
     
        public function getGridUrl()
        {
          return $this->getUrl('*/*/grid', array('_current'=>true));
        }


		protected function _prepareMassaction(){
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('brand');
			$this->getMassactionBlock()->addItem('delete', array(
				 'label'    => Mage::helper('brand')->__('Delete'),
				 'url'      => $this->getUrl('*/*/massDelete', array('store' => $this->getStoreId())),
				 'confirm'  => Mage::helper('brand')->__('Are you sure?')
			));
			$this->getMassactionBlock()->addItem('status', array(
				 'label'=> Mage::helper('brand')->__('Change status'),
				 'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true, 'store' => $this->getStoreId())),
				 'additional' => array(
						'visibility' => array(
							 'name' => 'status',
							 'type' => 'select',
							 'class' => 'required-entry',
							 'label' => Mage::helper('brand')->__('Status'),
							 'values' => array('1'=>'Active','0'=>'InActive')
						 )
				 )
			));
			return $this;
		}
     
}