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
class Tm_Offermanager_Model_Rules extends Varien_Object
{
	/**
     * Get all rules and display it as a droppdown
	 *
	 * @return array
     */
    public function getOptionArray()
    {
		$ruleModel = Mage::getModel('catalogrule/rule')->getCollection()->getData();
		$option = array(''=>'--Please select rule--');
		foreach ($ruleModel as $key => $data) {		
			$option[$data['rule_id']] = $data['name'];
		}
        return $option;
    }
}
