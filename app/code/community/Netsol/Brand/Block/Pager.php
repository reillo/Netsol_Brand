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
class Netsol_Brand_Block_Pager extends Mage_Page_Block_Html_Pager{
	
	protected function _construct()
    {  
        parent::_construct();
        $this->setData('show_amounts', true);
        $this->setData('use_container', true);
        $this->setTemplate('brand/html/pager.phtml'); 
    }
    
      public function getCurrentPage()
    {
        if ($page = (int) $this->getRequest()->getParam($this->getPageVarName())) {
            return $page;
        }
        return 1;
    }

    public function getLimit()
    {
        if ($this->_limit !== null) {
            return $this->_limit;
        }
        $limits = $this->getAvailableLimit();
        if ($limit = $this->getRequest()->getParam($this->getLimitVarName())) {
            if (isset($limits[$limit])) {
                return $limit;
            }
        }
        $limits = array_keys($limits);
        return $limits[0];
    }
    
	public function setCollection($collection)
    { 
       $this->_collection = $collection->setCurPage($this->getCurrentPage());
        // If not int - then not limit
        if ((int) $this->getLimit()) {
            $this->_collection->setPageSize($this->getLimit());
        }

        $this->_setFrameInitialized(false);
	
        return $this;
    }
	/***
     * Retrieve brand collection
     * 
     * 
     * @return Brand collection according to params
     * */    
	public function getMyCollection(){  
	  if (is_null($this->_myCollection)) 
	  {
		$this->_myCollection = Mage::getModel('brand/brand')->getCollection(); 
		$currentUrl = $this->helper('core/url')->getCurrentUrl(false);
		$url = Mage::getSingleton('core/url')->parseUrl($currentUrl);
		$path = $url->getPath();
		$brandproducturlsuffix = '.html';
		$brand_name = basename($path,$brandproducturlsuffix );
		$collections = Mage::getModel('brand/brand')->getCollection()
						->addFieldToSelect('brand_id')
						->addFieldToSelect('option_id')
						->addFieldToFilter('name', $brand_name);
		$brand = $collections->getFirstItem();

		$sAttributeName = 'brand_name';
		$this->_myCollection = Mage::getModel('catalog/product')->getCollection()
						->addAttributeToSelect('*')
						->addAttributeToFilter('visibility', 4)
						->addAttributeToFilter($sAttributeName, array('eq' => $brand->getOptionId()));															            
	 }    
	 return $this->_myCollection;   

	}
	
	 /**
     * Retrieve Pager URL
     *
     * @param string $order
     * @param string $direction
     * @return string
     */
    public function getOrderUrl($order, $direction)
    {
        if (is_null($order)) {
            $order = $this->getCurrentOrder() ? $this->getCurrentOrder() : $this->_availableOrder[0];
        }
        return $this->getPagerUrl(array(
            $this->getOrderVarName()=>$order,
            $this->getDirectionVarName()=>$direction,
            $this->getPageVarName() => null
        ));
    }
    public function getPages()
    { 
        $collection = $this->getMyCollection();

        $pages = array();
        if ($collection->getLastPageNumber() <= $this->_displayPages) {
            $pages = range(1, $collection->getLastPageNumber());
        }
        else {
            $half = ceil($this->_displayPages / 2);
            if ($collection->getCurPage() >= $half && $collection->getCurPage() <= $collection->getLastPageNumber() - $half) {
                $start  = ($collection->getCurPage() - $half) + 1;
                $finish = ($start + $this->_displayPages) - 1;
            }
            elseif ($collection->getCurPage() < $half) {
                $start  = 1;
                $finish = $this->_displayPages;
            }
            elseif ($collection->getCurPage() > ($collection->getLastPageNumber() - $half)) {
                $finish = $collection->getLastPageNumber();
                $start  = $finish - $this->_displayPages + 1;
            }

            $pages = range($start, $finish);
        }
        return $pages;
    }

    public function getFirstPageUrl()
    {
        return $this->getPageUrl(1);
    }

    public function getPreviousPageUrl()
    {
        return $this->getPageUrl($this->getMyCollection()->getCurPage(-1));
    }

    public function getNextPageUrl()
    {
        return $this->getPageUrl($this->getMyCollection()->getCurPage(+1));
    }

    public function getLastPageUrl()
    {
        return $this->getPageUrl($this->getMyCollection()->getLastPageNumber());
    }

    public function getPageUrl($page)
    {
        return $this->getPagerUrl(array($this->getPageVarName()=>$page));
    }

    public function getLimitUrl($limit)
    {
        return $this->getPagerUrl(array($this->getLimitVarName()=>$limit));
    }
     /**
     * Return current URL with rewrites and additional parameters
     *
     * @param array $params Query parameters
     * @return string
     */
    public function getPagerUrl($params=array())
    { 
		$urlKey = trim($this->getRequest()->getPathInfo(), '/');
		$urlParams = array();
        $urlParams['_current']  = false;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
        return $this->getUrl($urlKey, $urlParams);
    }
}
?>
