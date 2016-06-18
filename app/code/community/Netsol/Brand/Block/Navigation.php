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
class Netsol_Brand_Block_Navigation extends Mage_Catalog_Block_Navigation{
	
	
	/***
	 * Get Product Attribute at top menu
	 * 
	 * */
	public function Brandcustommenu($level = 0, $isLast = false, $isFirst = false,
        $isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false){
		$brandurl = Mage::getStoreConfig('netsol_brand/settings/brand_url');
		if($brandurl == '')
		{
			$brandurl = 'brand';
		}
		$html = array();

        // prepare list item html classes
        $classes = array();
        $classes[] = 'level' . $level;
        $classes[] = 'nav-' . $this->_getItemPosition($level);
        //$classes[] = 'active';
        $currentUrl = $this->helper('core/url')->getCurrentUrl();
        if ($currentUrl == Mage::getBaseUrl().$brandurl) {
            $classes[] = 'active';
        }
        $linkClass = '';
        $outermostItemClass = 'level-top';
        if ( $outermostItemClass) {
            $classes[] = $outermostItemClass;
            $linkClass = ' class="'.$outermostItemClass.'"';
        }
        if ($isFirst) {
            $classes[] = 'first';
        }
        if ($isLast) {
            $classes[] = 'last';
        }
        
        // prepare list item attributes
        $attributes = array();
        if (count($classes) > 0) {
            $attributes['class'] = implode(' ', $classes);
        }
       
		// $attributes['onmouseover'] = 'toggleMenu(this,1)';
		// $attributes['onmouseout'] = 'toggleMenu(this,0)';

		//print_r($attributes); exit;
        // assemble list item with attributes
        $htmlLi = '<li';
        foreach ($attributes as $attrName => $attrValue) {
            $htmlLi .= ' ' . $attrName . '="' . str_replace('"', '\"', $attrValue) . '"';
        }
        $htmlLi .= '>';
        $html[] = $htmlLi;

		$brandtitle = Mage::getStoreConfig('netsol_brand/settings/brand_title');
		$brandMenuname = ($brandtitle == '') ? 'Brands' : $brandtitle;
        $html[] = '<a href="'.Mage::getBaseUrl().$brandurl.'"'.$linkClass.'>';
        $html[] = '<span>' . ucwords($brandMenuname) . '</span>';
        $html[] = '</a>';
        
         $html[] = '</li>';

        $html = implode("\n", $html);
        return $html;
	}
}
