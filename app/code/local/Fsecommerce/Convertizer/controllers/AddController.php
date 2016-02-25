<?php class Fsecommerce_Convertizer_AddController extends Mage_Core_Controller_Front_Action{
	public function productAction(){
		$sku			= $this->getRequest()->getParam('sku');
		Mage::log($sku,Null,'convertizer.log');
		$redirectUrl	= Mage::helper('core/url')->getHomeUrl();
		if(!empty($sku)){
			$_product 	= Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);  
			if($_product && $_product->isSaleable()){
				try{
					$product 		= Mage::getModel('catalog/product')->load($_product->getId());
					$cart 			= Mage::getModel('checkout/cart');
					$cart->init();
					$cart->addProduct($product, array( 'product_id' => $product->getId(), 'qty' => 1));
					$cart->save();
					$params = $this->getRequest()->getParams();
					unset($params[sku]);
					unset($params[orig_link]);
					Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
					$addparams = "?";
					
					foreach($params as $key => $param){
						$addparams .= $key . '=' . $param . '&';
					}
					$addparams = rtrim($addparams, "&");
					$redirectUrl	= Mage::helper('checkout/cart')->getCartUrl() . $addparams;
				}catch(Exception $e){
					Mage::getSingleton('core/session')->addNotice('Produkt konnte nicht dem Warenkorb hinzugefügt werden. Bitte prüfen Sie ggf. weitere Optionen.');
					$productUrl		= $this->getRequest()->getParam('orig_link');
					if($productUrl){
						$redirectUrl	= $productUrl;
					}else{
						$redirectUrl	= Mage::helper('core/url')->getHomeUrl();
					}
				}
			}
		}
		$this->_redirectUrl($redirectUrl);
	}

	public function cartAction(){
		$basket			= json_decode($this->getRequest()->getParam('basket'),true);
		$redirectUrl	= Mage::helper('core/url')->getHomeUrl();
		$result			= array();
		if(count($basket)){
			$cart 			= Mage::getModel('checkout/cart');
			$cart->init();
			foreach($basket[0] as $k => $v){
				$_product 	= Mage::getModel('catalog/product')->loadByAttribute('sku', $k);  
				if($_product && $_product->isSaleable()){
					try{
						$product 		= Mage::getModel('catalog/product')->load($_product->getId());
						$cart->addProduct($product, array( 'product_id' => $product->getId(), 'qty' => $v));
					}catch(Exception $e){
						Mage::getSingleton('core/session')->addNotice("Produkt (SKU: $k) konnte nicht dem Warenkorb hinzugefügt werden. Bitte prüfen Sie ggf. weitere Optionen.");
					}
				}else{
					Mage::getSingleton('core/session')->addNotice("Produkt (SKU: $k) konnte nicht dem Warenkorb hinzugefügt werden.");
				}
			}
			$cart->save();
			$redirectUrl	= Mage::helper('checkout/cart')->getCartUrl();
			$this->_redirectUrl($redirectUrl);
		}else{
			$this->_redirectUrl($redirectUrl);
		}
	}

}