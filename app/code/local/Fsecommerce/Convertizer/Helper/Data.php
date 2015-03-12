<?php

class Fsecommerce_Convertizer_Helper_Data extends Mage_Core_Helper_Abstract{
	
    const XML_PATH_ENABLED   		= 'fsecommerce_convertizer/tracking/enabled'; 
	const XML_PATH_TRACKINGCODE 	= 'fsecommerce_convertizer/tracking/track';
	const XML_PATH_SHIPPINGGOOGLE 	= 'fsecommerce_convertizer/feed/shipping_google';
	const XML_PATH_SHIPPINGGENERAL 	= 'fsecommerce_convertizer/feed/shipping_general';
	
	
	public function isEnabled(){return Mage::getStoreConfig( self::XML_PATH_ENABLED );}
	public function getTrackingCode(){return Mage::getStoreConfig( self::XML_PATH_TRACKINGCODE );}
	public function getGoogleShipping(){return Mage::getStoreConfig( self::XML_PATH_SHIPPINGGOOGLE );}
	public function getGeneralShipping(){return Mage::getStoreConfig( self::XML_PATH_SHIPPINGGENERAL );}
}
