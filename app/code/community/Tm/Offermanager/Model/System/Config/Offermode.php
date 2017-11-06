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
class Tm_Offermanager_Model_System_Config_Offermode
{
	/**
     * Set offer mode
     *
     * @return array
     */
	public function toOptionArray()
	{
		return array(
            array('value' => 1, 'label'=>Mage::helper('offermanager')->__('Atomatic')),
            array('value' => 0, 'label'=>Mage::helper('offermanager')->__('Manually')),
        );
	}
}
