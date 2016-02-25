<?php

class Fsecommerce_Convertizer_Adminhtml_FeedController extends Mage_Adminhtml_Controller_Action
{

    public function generateAction()
    {
		
       // set amount of entries per temp csv
		$chunksize 		= 500;
		$storeId   		= Mage::helper('fsecommerce_convertizer')->getStore();
		// get the store
		$defaultStore   = Mage::app()
							->getWebsite()
							->getDefaultGroup()
							->getDefaultStoreId();
		if(!isset($storeId) || $storeId = ""){
			$storeId = $defaultStore;
		}
		
		
		// get the products collection
		
		
		if(Mage::helper('fsecommerce_convertizer')->getCategoryEnabled()){
			
			$categoryids = explode(",",Mage::helper('fsecommerce_convertizer')->getCategory());

			$collection = Mage::getResourceModel('catalog/product_collection')
				->setStoreId($storeId)
				->joinField('category_id','catalog/category_product','category_id','product_id=entity_id',null,'left')
				->addAttributeToFilter('category_id', array('in' => $categoryids));
				
				$collection->getSelect()->group('e.entity_id');
		}else{
			$collection = Mage::getModel('catalog/product')->getCollection()
			->setStoreId($storeId);
		}
		
		$collection->addFieldToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);

		 // get the total amout of entries
		$entries    = count($collection);
		// delete files first
		$files = array();
		foreach(scandir('./') as $file)
		{
			if(substr(basename($file), 0, 15) != 'feed_tmp_chunk_') continue;
			if(file_exists($file)) unlink($file);
		}

		$data = array(
			array(
				'id',
				'parent_id',
				'variant_attribute',
				'variant_attribute_value',
				'shipping_status',
				'shipping',
				'custom_attribute_' . $this->getAttributeLabel(Mage::helper('fsecommerce_convertizer')->getCustomAttribute01()),
				'custom_attribute_' . $this->getAttributeLabel(Mage::helper('fsecommerce_convertizer')->getCustomAttribute02()),
				'custom_attribute_' . $this->getAttributeLabel(Mage::helper('fsecommerce_convertizer')->getCustomAttribute03()),
				'custom_attribute_' . $this->getAttributeLabel(Mage::helper('fsecommerce_convertizer')->getCustomAttribute04()),
				'custom_attribute_' . $this->getAttributeLabel(Mage::helper('fsecommerce_convertizer')->getCustomAttribute05()),
				$this->getAttributeLabel(Mage::helper('fsecommerce_convertizer')->getAdditionalAttribute01()),
				$this->getAttributeLabel(Mage::helper('fsecommerce_convertizer')->getAdditionalAttribute02()),
				$this->getAttributeLabel(Mage::helper('fsecommerce_convertizer')->getAdditionalAttribute03()),
				$this->getAttributeLabel(Mage::helper('fsecommerce_convertizer')->getAdditionalAttribute04()),
				$this->getAttributeLabel(Mage::helper('fsecommerce_convertizer')->getAdditionalAttribute05()),
				'keywords',
				'title',
				'description',
				'image_link',
				'additional_image_link_1',
				'additional_image_link_2',
				'additional_image_link_3',
				'additional_image_link_4',
				'additional_image_link_5',
				'additional_image_link_6',
				'additional_image_link_7',
				'additional_image_link_8',
				'additional_image_link_9',
				'additional_image_link_10',
				'availability',
				'google_product_category',
				'product_type',
				'link',
				'price',
				'sale_price',
				'no_singlepage'
			)
		);
		

		$i = 0;
		
	
		foreach($collection as $product)
		{
			$i++;

			#if($i > 50) break;

			$product->load('media_gallery');

			#if($product->getStatus() != 1 && !$export_disabled_products) continue;
			#if($product->getMenge() <= 0 && !$export_out_of_stock_products) continue;

			$gallery = $product->getMediaGallery();
			$BaseURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
			$mediaUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
			$data[] = array(
				$product->getSku(),
				$this->getParentSKU($product),
				$this->getVariantsLabel($product),
				$this->getVariantsValue($product),
				$this->getGoogleShipping($product),
				$this->getGeneralShipping($product),
				$this->getCustomAttr01($product),
				$this->getCustomAttr02($product),
				$this->getCustomAttr03($product),
				$this->getCustomAttr04($product),
				$this->getCustomAttr05($product),
				$this->getAdditionalAttr01($product),
				$this->getAdditionalAttr02($product),
				$this->getAdditionalAttr03($product),
				$this->getAdditionalAttr04($product),
				$this->getAdditionalAttr05($product),
				$this->getKeywords($product),
				$product->getName(),
				$product->getDescription(),
				Mage::getModel('catalog/product_media_config')->getMediaUrl($product->getImage()),
				isset($gallery['images'][0]) ? $mediaUrl . 'catalog/product' . $gallery['images'][0]['file'] : '',
				isset($gallery['images'][1]) ? $mediaUrl . 'catalog/product' . $gallery['images'][1]['file'] : '',
				isset($gallery['images'][2]) ? $mediaUrl . 'catalog/product' . $gallery['images'][2]['file'] : '',
				isset($gallery['images'][3]) ? $mediaUrl . 'catalog/product' . $gallery['images'][3]['file'] : '',
				isset($gallery['images'][4]) ? $mediaUrl . 'catalog/product' . $gallery['images'][4]['file'] : '',
				isset($gallery['images'][5]) ? $mediaUrl . 'catalog/product' . $gallery['images'][5]['file'] : '',
				isset($gallery['images'][6]) ? $mediaUrl . 'catalog/product' . $gallery['images'][6]['file'] : '',
				isset($gallery['images'][7]) ? $mediaUrl . 'catalog/product' . $gallery['images'][7]['file'] : '',
				isset($gallery['images'][8]) ? $mediaUrl . 'catalog/product' . $gallery['images'][8]['file'] : '',
				isset($gallery['images'][9]) ? $mediaUrl . 'catalog/product' . $gallery['images'][9]['file'] : '',
				($product->getStatus() == 1 ? 'available' : 'not available'),
				$this->getProductCategory($product),
				$product->getProductType(),
				$BaseURL . $product->getUrlPath(),
				$product->getPrice(),
				$product->getFinalPrice() != $product->getPrice() ? $product->getFinalPrice() : '',
				$this->getExcludeOptions($product)
			);
			

			if($i % $chunksize == 0 || $i == $entries)
			{
				$fp = fopen('feed_tmp_chunk_' . $this->addzero($i, strlen($entries)) . '.csv', 'w');
				foreach ($data as $fields) fputcsv($fp, $fields, ';');
				fclose($fp);
				ob_clean();
				$data = array();
			}
		}

		// merge feed files and output
		$files = array();

		foreach(scandir('./') as $file)
		{
			if(substr(basename($file), 0, 15) != 'feed_tmp_chunk_') continue;
			$files[] = $file;
		}

		ob_end_clean();
		
		//$result = fopen('php://output', 'w');
		$csv = "";
		foreach($files as $i => $file)
		 {
			 $content = $i == count($files) - 1 ? trim(file_get_contents($file)) : file_get_contents($file);
			 //fwrite($result, $content);
			 $csv = $this->joinFiles($files, 'feed.csv');
		 }
		 
		file_put_contents(sprintf('%s/convertizer', Mage::getBaseDir('media')) . '/' . 'convertizer_feed.csv', $csv);
		
		fclose($result);

		// delete feed files
		 foreach($files as $i => $file)
		{
			if(file_exists($file)) unlink($file);
		}

	}
	
	public function getAttributeLabel($attributecode){
				$attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
				->setCodeFilter($attributecode)
				->getFirstItem();	
				$result = $attributeInfo->getFrontendLabel();
				return $result;
	}
	
	public function getCustomAttr01($product){
		$customAttribute = Mage::helper('fsecommerce_convertizer')->getCustomAttribute01();
		if($customAttribute && $customAttribute != "empty"){
			$result = $product->getResource()->getAttribute($customAttribute)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	
	public function getCustomAttr02($product){
		$customAttribute = Mage::helper('fsecommerce_convertizer')->getCustomAttribute02();
		if($customAttribute && $customAttribute != "empty"){
			$result = $product->getResource()->getAttribute($customAttribute)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	
	public function getCustomAttr03($product){
		$customAttribute = Mage::helper('fsecommerce_convertizer')->getCustomAttribute03();
		if($customAttribute && $customAttribute != "empty"){
			$result = $product->getResource()->getAttribute($customAttribute)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	
	public function getCustomAttr04($product){
		$customAttribute = Mage::helper('fsecommerce_convertizer')->getCustomAttribute04();
		if($customAttribute && $customAttribute != "empty"){
			$result = $product->getResource()->getAttribute($customAttribute)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	
	public function getCustomAttr05($product){
		$customAttribute = Mage::helper('fsecommerce_convertizer')->getCustomAttribute05();
		if($customAttribute && $customAttribute != "empty"){
			$result = $product->getResource()->getAttribute($customAttribute)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	
	public function getAdditionalAttr01($product){
		$AdditionalAttribute = Mage::helper('fsecommerce_convertizer')->getAdditionalAttribute01();
		if($AdditionalAttribute && $AdditionalAttribute != "empty"){
			$result = $product->getResource()->getAttribute($AdditionalAttribute)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	public function getAdditionalAttr02($product){
		$AdditionalAttribute = Mage::helper('fsecommerce_convertizer')->getAdditionalAttribute02();
		if($AdditionalAttribute && $AdditionalAttribute != "empty"){
			$result = $product->getResource()->getAttribute($AdditionalAttribute)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	public function getAdditionalAttr03($product){
		$AdditionalAttribute = Mage::helper('fsecommerce_convertizer')->getAdditionalAttribute03();
		if($AdditionalAttribute && $AdditionalAttribute != "empty"){
			$result = $product->getResource()->getAttribute($AdditionalAttribute)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	public function getAdditionalAttr04($product){
		$AdditionalAttribute = Mage::helper('fsecommerce_convertizer')->getAdditionalAttribute04();
		if($AdditionalAttribute && $AdditionalAttribute != "empty"){
			$result = $product->getResource()->getAttribute($AdditionalAttribute)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	public function getAdditionalAttr05($product){
		$AdditionalAttribute = Mage::helper('fsecommerce_convertizer')->getAdditionalAttribute05();
		if($AdditionalAttribute && $AdditionalAttribute != "empty"){
			$result = $product->getResource()->getAttribute($AdditionalAttribute)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	
	public function getProductCategory($product){
		$category = Mage::helper('fsecommerce_convertizer')->getProductCategory();
		if($category && $category != "empty"){
			$result = $product->getResource()->getAttribute($category)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	
	public function getKeywords($product){
		$keywords = Mage::helper('fsecommerce_convertizer')->getKeywords();
		if($keywords && $keywords != "empty"){
			$result = $product->getResource()->getAttribute($keywords)
			->getFrontend()->getValue($product);
			return $result;
		}else{
			return false;
		}
	}
	
	public function getGoogleShipping($product){
		
		$attrCode	= Mage::helper('fsecommerce_convertizer')->getGoogleShipping();
		if($attrCode && $attrCode != "empty"){
			$result = $product->getResource()->getAttribute($attrCode)
			->getFrontend()->getValue($product);
		}else{
			return false;
		}
		if($result){
			return $result;
		}else{
			return false;
		}
		
	}
	
	public function getGeneralShipping($product){
		
		$attrCode	= Mage::helper('fsecommerce_convertizer')->getGeneralShipping();
		if($attrCode && $attrCode != "empty"){
			$result = $product->getResource()->getAttribute($attrCode)
			->getFrontend()->getValue($product);
		}else{
			return false;
		}
		if($result){
			return $result;
		}else{
			return false;
		}
	}
	
	public function getParentSKU($product){
		$parentArray = $this->getParentProduct($product);
		
		if(!count($parentArray) || !$parentArray){
			return false;
		}
		
		$parentSkuArray = array();
		if(count($parentArray) || $parentArray){
			foreach($parentArray as $parent){
					$parentSku 	= $parent->getSku();
					array_push($parentSkuArray, $parentSku);	
			}
		}
		if( count($parentSkuArray) > 0){
			$parentSkuArray = implode(",", $parentSkuArray);
			$parentSkuArray = rtrim($parentSkuArray, ",");
			return $parentSkuArray;
		}else{
			return false;
		}
	}
		
	public function getParentProduct($product){
		$parrentArray = array();
		if($product->getTypeId() == "simple"){
			$parentgroupedIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($product->getId());
			$parentconfigIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
			
			if(!count($parentconfigIds)){
				return false;
			}else{
				foreach ($parentconfigIds as $parentID){
					$parent 	= Mage::getModel('catalog/product')->load($parentID); 
					array_push($parrentArray,$parent);
				}
			}
		}
		return $parrentArray;
	}
	
	public function getVariantsLabel($product){
		// first get the parent Sku
		
		$parentArray = $this->getParentProduct($product);
		if(!count($parentArray) || !$parentArray){
			return false;
		}
		$result = "";
		foreach ($parentArray as $parent){
			
			if($parent){
				$productAttributeOptions = $parent->getTypeInstance(true)->getConfigurableAttributesAsArray($parent);
				$attributeOptions = array();
				foreach ($productAttributeOptions as $productAttribute) {
					$result .= $productAttribute['label'] . ",";
				}
			}
		}
		$result = rtrim($result, ",");
		return $result;
		
	}
	
	public function getVariantsValue($product){
			
		$parentArray = $this->getParentProduct($product);
		
		if(!count($parentArray) || !$parentArray){
			return false;
		}
		
		$result = "";
		foreach($parentArray as $parent){
			if($parent){
				$productAttributeOptions = $parent->getTypeInstance(true)->getConfigurableAttributesAsArray($parent);
				$attributeOptions = array();
				foreach ($productAttributeOptions as $productAttribute) {
					$code = $productAttribute['attribute_code'];
					$result .= $product->getAttributeText($code) . ",";	
				}
			}
		}
		$result = rtrim($result, ",");
		return $result;
	}
	
	public function getExcludeOptions($product){
		if(Mage::helper('fsecommerce_convertizer')->excludeOptions()){
			$opts = Mage::getSingleton('catalog/product_option')->getProductOptionCollection($product);
			$optsSize = $opts->getSize();
			if($optsSize){
				return '1';
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
	
	public function joinFiles(array $files, $result) {
		$fileStr = "";
		if(!is_array($files)) {
			throw new Exception('`$files` must be an array');
		}

		$wH = fopen($result, "w+");

		foreach($files as $file) {
			$fh = fopen($file, "r");
			while(!feof($fh)) {
				//fwrite($wH, fgets($fh));
				$fileStr .= fgets($fh);
			}
			fclose($fh);
			unset($fh);
			fwrite($wH, "\n"); //usually last line doesn't have a newline
		}
		
		fclose($wH);
		unset($wH);
		return $fileStr;
	}
	
	public function get_sale_price_effective_date($price, $sale_price, $from, $to)
	{
		if($price == $sale_price) return '';

		$ts_from = strtotime($from);
		$ts_to   = strtotime($to);
		$s_from  = date('Y-m-d', $ts_from) . 'T' . date('H:i', $ts_from) . '-0000';
		$s_to    = $ts_to != 0 ? date('Y-m-d', $ts_to) . 'T' . date('H:i', $ts_to) . '-0000' : '3000-01-01T00:00-0000';

		return $s_from . '/' . $s_to;
	}
	
	public function addzero($i, $length = 4)
	{
		$i_length    = strlen($i);
		$zero_length = $length - $i_length;

		return str_repeat('0', $zero_length) . $i;
	}
}