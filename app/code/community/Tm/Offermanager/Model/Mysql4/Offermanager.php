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
class Tm_Offermanager_Model_Mysql4_Offermanager extends Mage_Core_Model_Mysql4_Abstract
{
	/**
     * Initialize resources
     */
    public function _construct()
    {    
        $this->_init('offermanager/offermanager', 'offer_id');
    }
	
	/**
     * Check status and time period for each rules and get active offer list
	 *
	 * @return array
     */
	public function getRuleStatus($data)
	{
		$activeOffers = array();
		if (!empty($data)) {
			foreach ($data as $key => $value) {
				$ruleId = $value['rule_id'];
				$ruleModel = Mage::getModel('catalogrule/rule')->getCollection()
															->addFieldToFilter('rule_id', $ruleId)
															->getData();
				$currentDate = date("Y-m-d", Mage::getModel('core/date')->timestamp(time()));
				foreach ($ruleModel as $key => $ruleData) {
					if ($ruleData['is_active'] == 1) {
						if ($ruleData['to_date'] >= $currentDate ) {
							$activeOffers[] = $value;
						} elseif (empty($ruleData['to_date']) && !empty($ruleData['from_date'])) {
							$activeOffers[] = $value;
						}
					}
				}
			}
		}
		return $activeOffers;
	}
}
