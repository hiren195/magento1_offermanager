<?php 
/**
 * Tm Offermanager
 *
 * Promote Your offers on selected page to enhanced sale
 *
 * 
 * @category   Tm
 * @package    Offermanager
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Tm_Offermanager_Block_Viewalloffers extends Mage_Core_Block_Template
{
	/**
     * Retrive all active offers
     *
     * @return array
     */
	public function getAllActiveOffers()
	{
		$offerModel = Mage::getModel('offermanager/offermanager')->getCollection()
															->addFieldToFilter('status', '1')
															->getData();														
		$activeOffers = Mage::getResourceModel('offermanager/offermanager')->getRuleStatus($offerModel);
		return $activeOffers;
	}
} 