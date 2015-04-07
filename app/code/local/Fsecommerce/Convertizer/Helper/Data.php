<?php

class Fsecommerce_Convertizer_Helper_Data extends Mage_Core_Helper_Abstract{
	
    const XML_PATH_ENABLED   			= 'fsecommerce_convertizer/tracking/enabled'; 
	const XML_PATH_TRACKINGCODE 		= 'fsecommerce_convertizer/tracking/track';
	const XML_PATH_SHIPPINGGOOGLE 		= 'fsecommerce_convertizer/feed/shipping_google';
	const XML_PATH_SHIPPINGGENERAL 		= 'fsecommerce_convertizer/feed/shipping_general';
	const XML_PATH_CATEGORY_ENABLED 	= 'fsecommerce_convertizer/feed/category_filter_enabled';
	const XML_PATH_CATEGORY 			= 'fsecommerce_convertizer/feed/category_filter';
	const XML_PATH_STORE 				= 'fsecommerce_convertizer/cron/default_store';
	const XML_PATH_CRON_ENABLED 		= 'fsecommerce_convertizer/cron/cron_enabled';
	
	
	public function isEnabled(){return Mage::getStoreConfig( self::XML_PATH_ENABLED );}
	public function getTrackingCode(){return Mage::getStoreConfig( self::XML_PATH_TRACKINGCODE );}
	public function getGoogleShipping(){return Mage::getStoreConfig( self::XML_PATH_SHIPPINGGOOGLE );}
	public function getGeneralShipping(){return Mage::getStoreConfig( self::XML_PATH_SHIPPINGGENERAL );}
	public function getCategoryEnabled(){return Mage::getStoreConfig( self::XML_PATH_CATEGORY_ENABLED );}
	public function getCategory(){return Mage::getStoreConfig( self::XML_PATH_CATEGORY );}
	public function getStore(){return Mage::getStoreConfig( self::XML_PATH_STORE );}
	public function isCronEnabled(){return Mage::getStoreConfig( self::XML_PATH_CRON_ENABLED );}
}
