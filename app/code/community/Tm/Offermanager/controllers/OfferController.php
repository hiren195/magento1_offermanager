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
class Tm_Offermanager_OfferController extends Mage_Core_Controller_Front_Action
{
	/**
     * Set layout for product page
     */
    public function productsAction()
    {
		$this->loadLayout();     
		$this->renderLayout();
    }
	
	/**
     * Set layout for view all offers page
     */
	public function viewalloffersAction()
	{
		$this->loadLayout();     
		$this->renderLayout();
	}
}