<?php
class Fsecommerce_Convertizer_Model_Adminhtml_Producttype
{
    public function toOptionArray($addEmpty = true)
    {
        $collection = Mage::getModel('catalog/product_type')->getOptionArray();
        $options = array();
        if ($addEmpty) {
            $options[] = array(
                'label' => Mage::helper('adminhtml')->__('-- Please Select Product Types --'),
                'value' => ''
            );
        }
        foreach ($collection as $key => $value) {
            $options[] = array(
               'label' => $value,
               'value' => $key
            );
        }
        return $options;
    }
}
