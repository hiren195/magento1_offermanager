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
class Tm_Offermanager_Model_System_Config_Source_Alloffers
{
	/**
     * Get all the offers for multiselect system config
     *
     * @return array
     */
	public function toOptionArray($isMultiselect=false) 
	{
		$options = array();
		$offerModel = Mage::getModel('offermanager/offermanager')->getCollection()->getData();
		foreach ($offerModel as $key => $data) {
			$options[]=array('value'=>$data['offer_id'],'label'=>$data['offer_name']);
		}
		return $options;
    }
}
