<?php

class Fsecommerce_Convertizer_Model_Adminhtml_Attribute extends Mage_Adminhtml_Model_System_Store
{
    protected $_url;

    public function toOptionArray()
    {
        $options = array('empty'=>'AAA Empty');
		
		
		$attributes = Mage::getResourceModel('catalog/product_attribute_collection')
		->getItems();

		foreach ($attributes as $attribute){
			$attrCode = $attribute->getAttributecode();
			$options[$attrCode] = $attribute->getFrontendLabel();
		}
		
        

        return $options;
    }

    
}
