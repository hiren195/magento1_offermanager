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
class Tm_Offermanager_Block_Adminhtml_Offermanager extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
    * Class constructor
    */
    public function __construct()
    {
        $this->_controller = 'adminhtml_offermanager';
        $this->_blockGroup = 'offermanager';
        $this->_headerText = Mage::helper('offermanager')->__('Offer Manager');
        $this->_addButtonLabel = Mage::helper('offermanager')->__('Add Offer');
        parent::__construct();
    }
}
