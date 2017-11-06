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
class Tm_Offermanager_Model_Offermanager extends Mage_Core_Model_Abstract
{
	/**
     * Initialize resources
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('offermanager/offermanager');
    }
}
