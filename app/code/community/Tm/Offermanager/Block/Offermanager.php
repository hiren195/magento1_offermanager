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
class Tm_Offermanager_Block_Offermanager extends Mage_Core_Block_Template
{
	/**
     * Preparing layout
     */
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
	/**
     * Retrive active offers for sidebar
     *
     * @return array
     */
    public function getOffers()     
    { 
       $offerMode = Mage::getStoreConfig('offermanager/managesidebar/offer_type');
	   $setOffer = Mage::getStoreConfig('offermanager/managesidebar/offers');
	   $selectedOffer = Mage::getStoreConfig('offermanager/managesidebar/select_offers');
	  
		//if offer mode is autometic then get enabled offer to display in sidebar
	   if (isset($offerMode) && $offerMode==1) {
			$offerModel = Mage::getModel('offermanager/offermanager')->getCollection()
																->addFieldToFilter('status', '1');
			if (isset($setOffer) && !empty($setOffer)) {
				$offerModel->getSelect()->limit($setOffer);
				$data = $offerModel->getData();
			} else {
				$offerModel->getSelect()->limit(5);
				$data = $offerModel->getData();
			}			
	    } elseif (isset($offerMode) && $offerMode == 0) {
			//if offer mode is manual then get selected offers and then display them on sidrbar
			$offer=explode(',', $selectedOffer);
			$offerModel = Mage::getModel('offermanager/offermanager')->getCollection()
																->addFieldToFilter('status', '1')
																->addFieldToFilter('offer_id', array('in'=>$offer));
			$data = $offerModel->getData();
	    }
		$activeOffers = Mage::getResourceModel('offermanager/offermanager')->getRuleStatus($data);
		return $activeOffers;
    }	
}