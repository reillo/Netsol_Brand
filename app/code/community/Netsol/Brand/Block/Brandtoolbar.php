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
class Netsol_Brand_Block_Brandtoolbar extends Mage_Catalog_Block_Product_List_Toolbar{
	
	protected $_myCollection;  
	
	protected function _prepareLayout(){  
		 parent::_prepareLayout(); 
		 $pager = $this->getLayout()->createBlock('page/html_pager','brand/html_pager')->setCollection($this->getMyCollection()); 
		 $this->setChild('pager', $pager); 
		 $this->getMyCollection()->load();   
		 return $this; 
	 }
	  
	/*public function getPagerHtml() 
	{   
		return $this->getChildHtml('pager');  
	}    */
	  
	public function getTotalNum()
    {
        return $this->getMyCollection()->getSize();
    }
	public function getLastPageNum()
    {
        return $this->getMyCollection()->getLastPageNumber();
    }
    
     /**
     * Init Toolbar
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_orderField  = Mage::getStoreConfig(
            Mage_Catalog_Model_Config::XML_PATH_LIST_DEFAULT_SORT_BY
        );

        $this->_availableOrder = $this->_getConfig()->getAttributeUsedForSortByArray();

        switch (Mage::getStoreConfig('catalog/frontend/list_mode')) {
            case 'grid':
                $this->_availableMode = array('grid' => $this->__('Grid'));
                break;

            case 'list':
                $this->_availableMode = array('list' => $this->__('List'));
                break;

            case 'grid-list':
                $this->_availableMode = array('grid' => $this->__('Grid'), 'list' =>  $this->__('List'));
                break;

            case 'list-grid':
                $this->_availableMode = array('list' => $this->__('List'), 'grid' => $this->__('Grid'));
                break;
        }
        $this->setTemplate('brand/toolbar/brandtoolbar.phtml');
    }
    /***
     * Retrieve brand collection
     * 
     * 
     * @return Brand collection according to params
     * */    
	protected function getMyCollection(){  
	  if (is_null($this->_myCollection)) {
		$this->_myCollection = Mage::getModel('brand/brand')->getCollection(); 
		$currentUrl = $this->helper('core/url')->getCurrentUrl(false);
		$url = Mage::getSingleton('core/url')->parseUrl($currentUrl);
		$path = $url->getPath();
		$path = urldecode($path);
		
		$brandproducturlsuffix = '.html';
		$brand_name = basename($path,$brandproducturlsuffix );
		//$brand_name = htmlentities($brand_name);
		
		$collections = Mage::getModel('brand/brand')->getCollection()
						->addFieldToSelect('brand_id')
						->addFieldToSelect('option_id')
						->addFieldToFilter('name', $brand_name);
		$brand = $collections->getFirstItem();

		$sAttributeName = 'brand_name';
		$this->_myCollection = Mage::getModel('catalog/product')->getCollection()
						->addAttributeToSelect('*')
						->addAttributeToFilter(
							'status',
							array('eq' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED) 
								//replace DISABLED to ENABLED for products with status enabled
						)
						->addWebsiteFilter()
						->addFieldToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
						->addAttributeToFilter($sAttributeName, array('eq' => $brand->getOptionId()));
		 Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($this->_myCollection);															            
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
    
     /**
     * Render pagination HTML
     *
     * @return string
     */
    public function getPagerHtml()
    {
        $pagerBlock = $this->getChild('brand_html_pager');

        if ($pagerBlock instanceof Varien_Object) {

            /* @var $pagerBlock Mage_Page_Block_Html_Pager */
            $pagerBlock->setAvailableLimit($this->getAvailableLimit());

            $pagerBlock->setUseContainer(false)
					->setShowPerPage(false)
					->setShowAmounts(false)
					->setLimitVarName($this->getLimitVarName())
					->setPageVarName($this->getPageVarName())
					->setLimit($this->getLimit())
					->setFrameLength(Mage::getStoreConfig('design/pagination/pagination_frame'))
					->setJump(Mage::getStoreConfig('design/pagination/pagination_frame_skip'))
					->setCollection($this->getMyCollection());

            return $pagerBlock->toHtml();
        }

        return '';
    }
}

?>
