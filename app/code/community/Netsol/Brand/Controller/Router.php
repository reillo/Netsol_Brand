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
class Netsol_Brand_Controller_Router extends Mage_Core_Controller_Varien_Router_Standard{
	/**
     * Match the request
     *
     * @param Zend_Controller_Request_Http $request
     * @return boolean
     */
    public function match(Zend_Controller_Request_Http $request)
    { 
		$moduleenabled = Mage::getStoreConfig('netsol_brand/settings/status');
		$modulebrandurl = Mage::getStoreConfig('netsol_brand/settings/brand_url');
		if($modulebrandurl == '')
		{
			$modulebrandurl = 'brand';
		}
        //checking before even try to find out that current module
        //should use this router
        if (!$this->_beforeModuleMatch()) { 
            return false;
        }

        $this->fetchDefault(); 

         $front = $this->getFront();
         $path = trim($request->getPathInfo(), '/');

        if ($path) {
            $p = explode('/', $path);
        } else {
            $p = explode('/', $this->_getDefaultPath());
        }
        
        // get module name
        if ($request->getModuleName()) {
           $module = $request->getModuleName(); 
        } else {
            if (!empty($p[0])) {
              $module = $p[0];
            } else {
                $module = $this->getFront()->getDefault('module');
                $request->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, '');
            }
        }

       
        if (!$module) {
            if (Mage::app()->getStore()->isAdmin()) {
                $module = 'admin';
            } else {
                return false;
            }
        }
	
       if(($module == $modulebrandurl) && $moduleenabled){ 
		   
		   if($p[1] && $p[0] == $modulebrandurl ){
			   $module = 'brand';
			   $controller = 'brand';
			   $action = 'view';
			   $request->setModuleName($module);
			   $request->setControllerName($controller);
			   $request->setActionName($action);
		   }elseif($p[0] == $modulebrandurl){
			   $module = 'brand';
			   $controller = 'brand';
			   $action = 'index';
			   $request->setModuleName($module);
			   $request->setControllerName($controller);
			   $request->setActionName($action);
		 }
	   }else{
		   /**
				 * Searching router args by module name from route using it as key
				 */
				$modules = $this->getModuleByFrontName($module);

				if ($modules === false) {
					return false;
				}

				//checkings after we foundout that this router should be used for current module
				if (!$this->_afterModuleMatch()) {
					return false;
				}

				/**
				 * Going through modules to find appropriate controller
				 */
				$found = false;
				foreach ($modules as $realModule) {
					$request->setRouteName($this->getRouteByFrontName($module));

					// get controller name
					if ($request->getControllerName()) {
						$controller = $request->getControllerName();
					} else {
						if (!empty($p[1])) {
							$controller = $p[1];
						} else {
							$controller = $front->getDefault('controller');
							$request->setAlias(
								Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
								ltrim($request->getOriginalPathInfo(), '/')
							);
						}
					}

					// get action name
					if (empty($action)) {
						if ($request->getActionName()) {
							$action = $request->getActionName();
						} else {
							$action = !empty($p[2]) ? $p[2] : $front->getDefault('action');
						}
					}

					//checking if this place should be secure
					$this->_checkShouldBeSecure($request, '/'.$module.'/'.$controller.'/'.$action);

					$controllerClassName = $this->_validateControllerClassName($realModule, $controller);
					if (!$controllerClassName) {
						continue;
					}

					// instantiate controller class
					$controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $front->getResponse());

					if (!$controllerInstance->hasAction($action)) {
						continue;
					}

					$found = true;
					break;
				}

				/**
				 * if we did not found any siutibul
				 */
				if (!$found) {
					if ($this->_noRouteShouldBeApplied()) {
						$controller = 'index';
						$action = 'noroute';

						$controllerClassName = $this->_validateControllerClassName($realModule, $controller);
						if (!$controllerClassName) {
							return false;
						}

						// instantiate controller class
						$controllerInstance = Mage::getControllerInstance($controllerClassName, $request,
							$front->getResponse());

						if (!$controllerInstance->hasAction($action)) {
							return false;
						}
					} else {
						return false;
					}
				}

				// set values only after all the checks are done
				$request->setModuleName($module);
				$request->setControllerName($controller);
				$request->setActionName($action);
				$request->setControllerModule($realModule);

				// set parameters from pathinfo
				for ($i = 3, $l = sizeof($p); $i < $l; $i += 2) {
					$request->setParam($p[$i], isset($p[$i+1]) ? urldecode($p[$i+1]) : '');
				}

				// dispatch action
				$request->setDispatched(true);
				$controllerInstance->dispatch($action);
		   
	   }
	   

       return true;
    
	}
}

