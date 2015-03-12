<?php 

class Fsecommerce_Convertizer_Block_Adminhtml_Feed extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('convertizer/feed.phtml');
    }
 
    /**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }
 
    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function generateFeed()
    {	
		return Mage::helper('adminhtml')->getUrl('convertizer/adminhtml_feed/generate');
    }
	
	public function getFeedData(){
		return Mage::helper('adminhtml')->getUrl('convertizer/adminhtml_feed/generate');
	}
	
	public function showResult(){
		$result = "Success";
		return $result;
	}
	
    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
            'id'        => 'convertizer_button',
            'label'     => $this->helper('adminhtml')->__('Generate'),
            'onclick'   => 'javascript:generateconvertizerfeed(); return false;'
        ));
 
        return $button->toHtml();
    }
}