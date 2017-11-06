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
class Tm_Offermanager_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

	/**
     * get status as an array
	 *
	 * @return array
     */
    static public function getOptionArray()
    {
        return array(
            self::STATUS_ENABLED    => Mage::helper('offermanager')->__('Enabled'),
            self::STATUS_DISABLED   => Mage::helper('offermanager')->__('Disabled')
        );
    }
}
