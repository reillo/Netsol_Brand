<?php

$installer = $this;
$installer->startSetup();
 $installer->addAttribute('catalog_product', 'brand_name', array(
  'attribute_set_name' => 'Default',
  'group' => 'General', 
   'type'              => 'int',
  'backend'           => '',
  'frontend'          => '',
  'label'             => 'Brand Name',
  'input'             => 'select',
  'class'             => '',
  'visible'           => true,
  'required'          => false,
  'user_defined'      => false,
  'default'           => '',
  'searchable'        => false,
  'filterable'        => false,
  'comparable'        => false,
  'visible_on_front'  => false,
  'unique'            => false,
 'is_configurable'=>'1',
'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,)); 
//$installer->removeAttribute('catalog_product', 'brand_name');

$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('brand')};
CREATE TABLE IF NOT EXISTS {$this->getTable('brand')} (
  `brand_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'brand id',
  `option_id` int(11) NOT NULL COMMENT 'the brand id set from attribute',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `is_feature` smallint(6) NOT NULL DEFAULT '0',
  `base_image` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Status',
  `created_time` timestamp NULL,
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8
 ");

?>
