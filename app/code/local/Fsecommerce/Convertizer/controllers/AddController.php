<?php /*
 * FS eCommerce GmbH
 * http://fs-ecommerce.com
 * Magento Modul for Convertizer App
 * http://convertizer-commerce.com
 * Version 0.1 beta
 */ ?>
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
					Mage::getSingleton('core/session')->addNotice('Produkt konnte nicht dem Warenkorb hinzugefügt werden.');
  					$redirectUrl	= Mage::helper('core/url')->getHomeUrl();
				}
			}
		}
		$this->_redirectUrl($redirectUrl);
	}
}