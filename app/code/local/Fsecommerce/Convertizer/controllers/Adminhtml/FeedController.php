<?php

class Fsecommerce_Convertizer_Adminhtml_FeedController extends Mage_Adminhtml_Controller_Action
{

    public function generateAction()
    {
		
       // set amount of entries per temp csv
		$chunksize = 500;
		// get the products collection
		$collection = Mage::getModel('catalog/product')->getCollection();
		#$collection->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
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
				'title',
				'description',
				'brand',
				'gtin',
				'color',
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
				'material',
				'google_product_category',
				'product_type',
				'link',
				'price',
				'sale_price',
				'sale_price_effective_date',
				'size',
				'weight',
				'energy_efficiency_class',
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
				$product->getName(),
				strip_tags($product->getDescription()),
				$product->getAttributeText('manufacturer'),
				$product->getEan(),
				$product->getColorName(),
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
				$product->getMaterialText(),
				$product->getGoogleProductCategory(),
				$product->getProductType(),
				$BaseURL . $product->getUrlPath(),
				$product->getPrice(),
				$product->getFinalPrice() != $product->getPrice() ? $product->getFinalPrice() : '',
				$this->get_sale_price_effective_date($product->getPrice(), $product->getFinalPrice(), $product->getSpecialFromDate(), $product->getSpecialToDate()),
				$product->getDimension(),
				$product->getWeight(),
				$product->getEnergyEfficiency(),
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
		
		$result = fopen('php://output', 'w');
		$csv = "";
		foreach($files as $i => $file)
		 {
			 $content = $i == count($files) - 1 ? trim(file_get_contents($file)) : file_get_contents($file);
			 fwrite($result, $content);
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
	
	public function getParentSKU($product){
		$parentArray = $this->getParentProduct($product);
		foreach ($parentArray as $parent){
			if($parent){
			$parentSku 	= $parent->getSku();
			return $parentSku;	
			}else{
				return false;
			}
		}
	}
		
	public function getParentProduct($product){
		$parrentArray = array();
		if($product->getTypeId() == "simple"){
				$parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($product->getId());
			if(!$parentIds){
				$parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($product->getId());
				if(!$parentIds){
					return false;
				}else{
					foreach ($parentIds as $parentID){
						$parent 	= Mage::getModel('catalog/product')->load($parentID); 
						array_push($parrentArray,$parent);
						return $parrentArray;
					}
				}
			}
		}
	}
	
	public function getVariantsLabel($product){
		// first get the parent Sku
		
		$parentArray = $this->getParentProduct($product);
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