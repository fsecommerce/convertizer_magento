<?php /*
 * FS eCommerce GmbH - Leipzig
 * http://fs-ecommerce.com
 * Magento Module for Convertizer App
 * http://convertizer-commerce.com
 * Version 0.1 beta
 * TODO: add original url to add to cart link
 */ ?>
<?php class Fsecommerce_Convertizer_AddController extends Mage_Core_Controller_Front_Action{
	public function productAction(){
		$sku			= $this->getRequest()->getParam('sku');
		$redirectUrl	= Mage::helper('core/url')->getHomeUrl();
		if(!empty($sku)){
			$_product 	= Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);  
			if($_product && $_product->isSaleable()){
				try{
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