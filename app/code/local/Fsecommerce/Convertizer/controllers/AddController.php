<?php class Fsecommerce_Convertizer_AddController extends Mage_Core_Controller_Front_Action{
	public function productAction(){
		$sku			= $this->getRequest()->getParam('sku');
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
					Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
					$redirectUrl	= Mage::helper('checkout/cart')->getCartUrl();
				}catch(Exception $e){
					Mage::log($e->getMessage(),null,'convertizer.log');
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
}