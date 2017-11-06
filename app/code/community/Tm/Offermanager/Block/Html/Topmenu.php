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
class Tm_Offermanager_Block_Html_Topmenu extends Mage_Page_Block_Html_Topmenu
{ 
	/**
     * Get active offer and set it to topmenu
     *
     * @return array
     */
	public function getOfferMenu()
	{
		$offerModel=Mage::getModel('offermanager/offermanager')->getCollection()
																->AddFieldToFilter('status','1')
																->getData();
		$activeOffers=Mage::getResourceModel('offermanager/offermanager')->getRuleStatus($offerModel);
		return $activeOffers;
	} 
 } 