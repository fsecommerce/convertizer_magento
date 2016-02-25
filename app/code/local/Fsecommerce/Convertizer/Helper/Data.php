<?php

class Fsecommerce_Convertizer_Helper_Data extends Mage_Core_Helper_Abstract{
	
    const XML_PATH_ENABLED   			= 'fsecommerce_convertizer/tracking/enabled'; 
	const XML_PATH_TRACKINGCODE 		= 'fsecommerce_convertizer/tracking/track';
	const XML_PATH_SHIPPINGGOOGLE 		= 'fsecommerce_convertizer/feed/shipping_google';
	const XML_PATH_SHIPPINGGENERAL 		= 'fsecommerce_convertizer/feed/shipping_general';
	
	const XML_PATH_CUSTOMATTR01 		= 'fsecommerce_convertizer/feed/custom_attr_01';
	const XML_PATH_CUSTOMATTR02 		= 'fsecommerce_convertizer/feed/custom_attr_02';
	const XML_PATH_CUSTOMATTR03 		= 'fsecommerce_convertizer/feed/custom_attr_03';
	const XML_PATH_CUSTOMATTR04 		= 'fsecommerce_convertizer/feed/custom_attr_04';
	const XML_PATH_CUSTOMATTR05 		= 'fsecommerce_convertizer/feed/custom_attr_05';
	
	const XML_PATH_ADDITIONALATTR01 		= 'fsecommerce_convertizer/feed/additional_attr_01';
	const XML_PATH_ADDITIONALATTR02 		= 'fsecommerce_convertizer/feed/additional_attr_02';
	const XML_PATH_ADDITIONALATTR03 		= 'fsecommerce_convertizer/feed/additional_attr_03';
	const XML_PATH_ADDITIONALATTR04 		= 'fsecommerce_convertizer/feed/additional_attr_04';
	const XML_PATH_ADDITIONALATTR05 		= 'fsecommerce_convertizer/feed/additional_attr_05';
	
	const XML_PATH_PRODUCTCATEGORY 		= 'fsecommerce_convertizer/feed/product_category';
	
	const XML_PATH_KEYWORDS 			= 'fsecommerce_convertizer/feed/keywords';
	
	const XML_PATH_CATEGORY_ENABLED 	= 'fsecommerce_convertizer/feed/category_filter_enabled';
	const XML_PATH_CATEGORY 			= 'fsecommerce_convertizer/feed/category_filter';
	const XML_PATH_STORE 				= 'fsecommerce_convertizer/cron/default_store';
	const XML_PATH_CRON_ENABLED 		= 'fsecommerce_convertizer/cron/cron_enabled';
	
	
	public function isEnabled(){return Mage::getStoreConfig( self::XML_PATH_ENABLED );}
	public function getTrackingCode(){return Mage::getStoreConfig( self::XML_PATH_TRACKINGCODE );}
	
	public function getCustomAttribute01(){return Mage::getStoreConfig( self::XML_PATH_CUSTOMATTR01 );}
	public function getCustomAttribute02(){return Mage::getStoreConfig( self::XML_PATH_CUSTOMATTR02 );}
	public function getCustomAttribute03(){return Mage::getStoreConfig( self::XML_PATH_CUSTOMATTR03 );}
	public function getCustomAttribute04(){return Mage::getStoreConfig( self::XML_PATH_CUSTOMATTR04 );}
	public function getCustomAttribute05(){return Mage::getStoreConfig( self::XML_PATH_CUSTOMATTR05 );}
	
	public function getKeywords(){return Mage::getStoreConfig( self::XML_PATH_KEYWORDS );}
	
	public function getAdditionalAttribute01(){return Mage::getStoreConfig( self::XML_PATH_ADDITIONALATTR01 );}
	public function getAdditionalAttribute02(){return Mage::getStoreConfig( self::XML_PATH_ADDITIONALATTR02 );}
	public function getAdditionalAttribute03(){return Mage::getStoreConfig( self::XML_PATH_ADDITIONALATTR03 );}
	public function getAdditionalAttribute04(){return Mage::getStoreConfig( self::XML_PATH_ADDITIONALATTR04 );}
	public function getAdditionalAttribute05(){return Mage::getStoreConfig( self::XML_PATH_ADDITIONALATTR05 );}
	
	public function getProductCategory(){return Mage::getStoreConfig( self::XML_PATH_PRODUCTCATEGORY );}
	
	public function getGoogleShipping(){return Mage::getStoreConfig( self::XML_PATH_SHIPPINGGOOGLE );}
	public function getGeneralShipping(){return Mage::getStoreConfig( self::XML_PATH_SHIPPINGGENERAL );}
	public function getCategoryEnabled(){return Mage::getStoreConfig( self::XML_PATH_CATEGORY_ENABLED );}
	public function getCategory(){return Mage::getStoreConfig( self::XML_PATH_CATEGORY );}
	public function getStore(){return Mage::getStoreConfig( self::XML_PATH_STORE );}
	public function isCronEnabled(){return Mage::getStoreConfig( self::XML_PATH_CRON_ENABLED );}
}
