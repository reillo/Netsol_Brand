<?php
    class Netsol_Brand_Block_Adminhtml_Brand_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $form = new Varien_Data_Form();
            $this->setForm($form);
            $fieldset = $form->addFieldset('brand_form', array('legend'=>Mage::helper('brand')->__('Brand information')));
           
            $fieldset->addField('name', 'text', array(
                'label'     => Mage::helper('brand')->__('Name'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'name',
            ));
			$fieldset->addField('option_id', 'hidden', array(
              'name'      => 'option_id',
            ));
            $fieldset->addField('status', 'select', array(
                'label'     => Mage::helper('brand')->__('Status'),
                'name'      => 'status',
                'values'    => array(
                    array(
                        'value'     => 1,
                        'label'     => Mage::helper('brand')->__('Active'),
                    ),
     
                    array(
                        'value'     => 0,
                        'label'     => Mage::helper('brand')->__('Inactive'),
                    ),
                ),
            ));

			$fieldset->addField('is_feature', 'select', array(
                'label'     => Mage::helper('brand')->__('Is Featured'),
                'name'      => 'is_feature',
                'values'    => array(
                    array(
                        'value'     => 1,
                        'label'     => Mage::helper('brand')->__('Yes'),
                    ),
     
                    array(
                        'value'     => 0,
                        'label'     => Mage::helper('brand')->__('No'),
                    ),
                ),
            ));

           
            $fieldset->addField('description', 'editor', array(
                'name'      => 'description',
                'label'     => Mage::helper('brand')->__('Description'),
                'title'     => Mage::helper('brand')->__('Description'),
                'style'     => 'width:98%;',
                'wysiwyg'   => false,
                'required'  => true,
            ));
		
			$fieldset->addField('logo', 'file', array(
			  'label'     => Mage::helper('brand')->__('Logo'),
			  'required'  => false,
			  'name'      => 'logo',
			  'after_element_html' => (''!=Mage::registry('brand_data')->getData('logo')?'<p style="margin-top: 5px"><img src="'.Mage::getBaseUrl('media') . 'brand/logo/' . Mage::registry('brand_data')->getData('logo').'" width="60px" height="60px" /><br /><a href="'.$this->getUrl('*/*/*/', array('_current'=>true, 'delete'=>'logo')).'">'.Mage::helper('brand')->__('Delete Logo').'</a></p>':''),      	  
			));
	  
		  $fieldset->addField('logo_', 'hidden', array(
			'name'      => 'logo_',
		  ));
		  Mage::registry('brand_data')->setData('logo_', Mage::registry('brand_data')->getData('logo'));
	  
		  $fieldset->addField('base_image', 'file', array(
			  'label'     => Mage::helper('brand')->__('Image'),
			  'required'  => false,
			  'name'      => 'base_image',
			  'after_element_html' => (''!=Mage::registry('brand_data')->getData('base_image')?'<p style="margin-top: 5px"><img src="'.Mage::getBaseUrl('media') . 'brand/' . Mage::registry('brand_data')->getData('base_image').'" width="60px" height="60px" /><br /><a href="'.$this->getUrl('*/*/*/', array('_current'=>true, 'delete'=>'base_image')).'">'.Mage::helper('brand')->__('Delete Image').'</a></p>':''),      	  
		  ));
		  
		  $fieldset->addField('base_image_', 'hidden', array(
			'name'      => 'base_image_',
		  ));
		  Mage::registry('brand_data')->setData('base_image_', Mage::registry('brand_data')->getData('base_image'));

           
			if ( Mage::getSingleton('adminhtml/session')->getBrandData() )
			{
				$form->setValues(Mage::getSingleton('adminhtml/session')->getBrandData());
				Mage::getSingleton('adminhtml/session')->setBrandData(null);
			} elseif ( Mage::registry('brand_data') ) {
				$form->setValues(Mage::registry('brand_data')->getData());
			}
            return parent::_prepareForm();
      }
}
?>