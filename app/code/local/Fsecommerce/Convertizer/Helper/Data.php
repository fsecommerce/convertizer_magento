<?php

class Fsecommerce_Convertizer_Helper_Data extends Mage_Core_Helper_Abstract{
	
    const XML_PATH_ENABLED   	= 'fsecommerce_convertizer/tracking/enabled'; 
	const XML_PATH_TRACKINGCODE = 'fsecommerce_convertizer/tracking/track';
	
	
	public function isEnabled(){return Mage::getStoreConfig( self::XML_PATH_ENABLED );}
	public function getTrackingCode(){return Mage::getStoreConfig( self::XML_PATH_TRACKINGCODE );}
}
