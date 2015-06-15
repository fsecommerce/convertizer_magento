# Magento eCommerce Module for convertizer
Magento Module to automatically handover products into your Shop-basket.
<br> 
After installation convertizer will be able to directly place items into your basket. With the help of this module users will
be forwarded automatically to your basket after they clicked on "add to basket" at your convertizer landing pages. The related product will be already placed in the basket so that they can proceed with the checkout immediately.
<br>
With the help of this module your conversion rate will be increased and drop-off rates reduced.
<br>
<h2>Installation Guide</h2>
<ol>
<li>Download the module as app folder</li>
<li>Upload the app folder in your Magento</li>
<li>Drop your backend cache of Magento</li>
<li>Start earning more money with convertizer</li>
</ol>
<h2>Please note: </h2>
if your product feed contains product ID's instead of sku please write an email to: <strong>support@convertizer.com</strong><br/>
We will take care of it right away!
<p>
<h2>The module is able to generate the needed convertizer feed with the following fields</h2>
id | The product article number<br/>
parent_id | If the line is a variant of a product, it places here the "SKU" (product article number) of the master product<br/>
title | Product name<br/>
variant_attribute | If the line is a variant of a product, it places here the attribute name, i.e. "color" <br/>
variant_attribute_value | If the line is a variant of a product, the module will set the matching values <br/>
custom_attribute_... | In the modules settings you can specify five custom attributes that will be output in tabs on the landingpage <br/>
description | The long description of the product<br/>
brand | brand of the product <br/>
gtin | the EAN or GTIN number of the product<br/>
image_link | Product image url<br/>
additional_image_link_1 | URL to a second image of the product<br/>
additional_image_link_2 | URL to a third image of the product<br/>
additional_image_link_3 | URL to a fourth image of the product<br/>
additional_image_link_4 | URL to a fifth image of the product<br/>
additional_image_link_5 | URL to a sixth image of the product<br/>
additional_image_link_6 | URL to a seventh image of the product<br/>
additional_image_link_7 | URL to a eight image of the product<br/>
additional_image_link_8 | URL to a ninth image of the product<br/>
additional_image_link_9 | URL to a tenth image of the product<br/>
shipping | cost of shipping, i.e. 1.99<br/>
availability | According to Google Merchant specifications you have 2 values. "preorder": You’re currently taking orders for this product, but it’s not yet been released. "in stock": You’re currently accepting orders for this product and can fulfil the purchase request. You are certain that the item will be shipped (or be in transit to the customer) in a timely manner because it is available for sale, such as it’s in stock or available for order. <br/>
google_product_category | The category of the product, seperated with ">" (even without google convertizer uses this information to print breadcrumbs on the landingpage.<br/>
link | The URL of the product detail page<br/>
price | The price of the product without currency information. The currency information is definied in the first step of the landingpage setup<br/>
sale price | reduced price if available. The price will be displayed strikedthrough and this price will appear<br/>
sale_price_effective_date | information about the period the sale price is valid. Format is strictly eg 2015-03-05T00:00-0000/2015-09-01T00:00-0000 (from/till)
search_title | <br/>
energy_efficiency_class | the EU energy efficiency class category, i.e. A++<br/>
<p>
<p>Most of the fields will be exported into the feed automaticly. Others you are able to set in the modules settings. Those are described there again.</p>

