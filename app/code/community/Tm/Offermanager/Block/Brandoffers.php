<?php 
/**
 * Tm Offermanager
 *
 * Promote Your offers on selected page to enhanced sale
 *
 *
 * @category    Tm
 * @package     Offermanager
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tm_Offermanager_Block_Brandoffers extends Mage_Core_Block_Template
{
    /**
     * Retrive if any brands related offer for advanced search resilt page
     *
     * @return array
     */
    public function getBrandsOffers()
    {
        $finalOffer=array();
        $brandsAttribute=Mage::getStoreConfig('offermanager/advancesearch_offers/brand_attribute');
        if (!empty($brandsAttribute) && isset($brandsAttribute)) {
            //get brand from url
            $brandIds = $this->getRequest()->getParam($brandsAttribute);
            $allOffers = Mage::getModel('offermanager/offermanager')->getCollection()
                                                                    ->addFieldToFilter('status', '1')
                                                                    ->getData();
																
            //get offers which is enable and not out of date.
            $activeOffers = Mage::getResourceModel('offermanager/offermanager')->getRuleStatus($allOffers);
            $brandId = array();
            
            foreach ($activeOffers as $key => $value) {
                $brandname = "";
                $str = "";
                $ruleId = $value['rule_id'];
                $ruleProducts = Mage::getModel('catalogrule/rule')->load($ruleId);		
                $unserializeCondition = unserialize($ruleProducts->getConditionsSerialized());
                $conditions = $unserializeCondition['conditions'];

                //check all condition for particular offer.
                foreach ($conditions as $data) {
                    // return path if brand's condition found
                    $path = $this->helper('offermanager')->recursiveSearchArray($brandsAttribute,$data);

                    if (!empty($path)) {
                        $count = count($path)-1;
                        $i = 0;
                        foreach ($path as $key => $path) {
                            if($i != $count) {
                                $data=$data[$path];
                            }
                            $i = $i+1;
                        }

                        if ($data['operator'] == '==' && !empty($data['value'])) {
                            $ruleBrandId = explode(',',$data['value']);
                            $brandsId = array_map('trim',$ruleBrandId);
                            $brandArray = array_intersect(array($brandIds), $brandsId);

                            // get all brands from magento
                            $attributeId = Mage::getResourceModel('eav/entity_attribute')
                                                                ->getIdByCode('catalog_product', $brandsAttribute);
                            $attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
                            $attributeOptions = $attribute ->getSource()->getAllOptions();

							foreach ($brandArray as $key => $brand) {
								foreach ($attributeOptions as $key => $option) {
									if ($option['value'] == $brand) {
										$str = $str.$option['label'].', ';
										$brandname = $option['label'];
									}
								}
							}
							
							if (!empty($str) && isset($str)) {
								$value['string'] = $brandname;
								$finalOffer['string'] = $str;
							}
							
							//make offer's array 
							if (!empty($brandArray)) {
								$finalOffer[] = $value;
							}
						} elseif ($data['operator'] == '!=' && !empty($data['value'])) {
							// make an array of brandids 
							$ruleBrandId = explode(',',$data['value']);
							$brandId[] = array_map('trim',$ruleBrandId);
							$brandId['offer_id'] = $value['offer_id'];
						}
					}
				}
				
				//get all other brand's when condition "!="
				if (isset($brandId) && !empty($brandId) && $brandId['offer_id'] == $value['offer_id']) {
					$notbrandId = array();
					foreach ($brandId as $key => $val) {
						$notbrandId[] = $val[0];
					}
					
					// get brand ids which is not present in $notbrandId array.
					$brandArray = array_diff(array($brandIds), $notbrandId);
					
					// get all brands from magento
					$attributeId = Mage::getResourceModel('eav/entity_attribute')
													->getIdByCode('catalog_product', $brandsAttribute);
					$attribute = Mage::getModel('catalog/resource_eav_attribute')->load($attributeId);
					$attributeOptions = $attribute ->getSource()->getAllOptions();
					
					foreach ($brandArray as $key => $brands) {
						foreach ($attributeOptions as $key => $option) {
							if ($option['value'] == $brands) {
								$str = $str.$option['label'].', ';
								$brandname = $option['label'];
							}
						}
					}
					
					if (!empty($str) && isset($str)) {
						$value['string'] = $str;
						$finalOffer['string'] = $str;
					}
					
					//make offer's array 
					if (!empty($brandArray)) {
						$finalOffer[] = $value;
					}
				}
			}
		}
		return $finalOffer;
	} 
}