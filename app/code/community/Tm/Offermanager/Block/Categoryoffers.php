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
class Tm_Offermanager_Block_Categoryoffers extends Mage_Core_Block_Template
{
	/**
     * Retrive category based offers
     *
     * @return array
     */
	public function getCategoryOffers()
	{
		$currentCategoryId = Mage::getModel('catalog/layer')->getCurrentCategory()->getId();
		$categoryCollection = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('all_children')
            ->addAttributeToFilter('entity_id',$currentCategoryId)
            ->load();

		foreach ($categoryCollection as $childrens) {
			$childIds = $childrens->getAllChildren();  //$child is the string seperated by comma (,).There are the childs ID 
		}
		$categoryIds = explode(',',$childIds);
		$allOffers = Mage::getModel('offermanager/offermanager')->getCollection()
															->addFieldToFilter('status', '1')
															->getData();
		$activeOffers = Mage::getResourceModel('offermanager/offermanager')->getRuleStatus($allOffers);
		$finalOffer = array();
		foreach ($activeOffers as $key => $value){
			$ruleId = $value['rule_id'];
			$ruleProducts = Mage::getModel('catalogrule/rule')->load($ruleId);		
			$unserializeCondition = unserialize($ruleProducts->getConditionsSerialized());
			$conditions = $unserializeCondition['conditions'];
			$path = $this->helper('offermanager')->recursiveSearchArray('category_ids', $conditions);
			if (!empty($path)){
				$count = count($path)-1;
				$i = 0;
				foreach ($path as $key => $path) {
					if ($i != $count) {
						$conditions = $conditions[$path];
					}
					$i = $i+1;
				}
				//specify positive In codition 
				if (($conditions['operator'] == '==' || $conditions['operator'] == '{}' || $conditions['operator'] == '()') && !empty($conditions['value'])) {
					$ruleCategoryId = explode(',', $conditions['value']);
					$categoryId = array_map('trim', $ruleCategoryId);
					$categoryArray = array_intersect($categoryIds, $categoryId);
					if (!empty($categoryArray)) {
						$finalOffer[] = $value;
					}
				} elseif(($conditions['operator'] == '!=' || $conditions['operator'] == '!{}' || $conditions['operator'] == '!()') && !empty($conditions['value'])) {	
					$ruleCategoryId = explode(',', $conditions['value']);
					$categoryId = array_map('trim', $ruleCategoryId);
					$categoryArray = array_diff($categoryIds, $categoryId);
					if (!empty($categoryArray)) {
						$finalOffer[] = $value;
					}
				}
			}
		}
		return $finalOffer;
	}
}