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
class Tm_Offermanager_Block_Product_List extends Mage_Catalog_Block_Product_List
{
	/**
     * Get all products for paricular offer and display it on offer page 
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
	Protected function _getProductCollection()
    { 
		if (is_null($this->_productCollection)) {
			$offerId=$this->getRequest()->getParam('id');
			$offerData=Mage::getModel('offermanager/offermanager')->getCollection()->addFieldToFilter('offer_id',$offerId)->getData();			
			foreach($offerData as $key=>$value) {
				$ruleId=$value['rule_id'];
			}	
			$ruleModel=Mage::getResourceModel('catalogrule/rule')->getRuleProductIds($ruleId);
			$productIds=array_unique($ruleModel);
			$this->_productCollection = Mage::getModel('catalog/product')->getCollection()
						->addAttributeToFilter('entity_id',array('in'=>$productIds))
						->addAttributeToFilter('visibility',array('neq'=>'1'))
						->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
						->addMinimalPrice()
						->addFinalPrice()
						->addTaxPercents();
		}
        return $this->_productCollection;
	}
	
	/**
     * Get offer on the base of offer id. 
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
	public function getOfferData()
	{
		$offerId=$this->getRequest()->getParam('id');
		$offerModel=Mage::getModel('offermanager/offermanager')->getCollection()
																->addFieldToFilter('offer_id',$offerId)
																->getData();
		
		return $offerModel;
	}
}