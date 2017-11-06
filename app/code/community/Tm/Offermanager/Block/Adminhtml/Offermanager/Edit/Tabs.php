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
class Tm_Offermanager_Block_Adminhtml_Offermanager_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Set title
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('offermanager_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('offermanager')->__('Offer Manager'));
    }

    /**
     * Add tab for the offermanager form
     *
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('offermanager')->__('Offer Manager'),
            'title'     => Mage::helper('offermanager')->__('Offer Manager'),
            'content'   => $this->getLayout()->createBlock('offermanager/adminhtml_offermanager_edit_tab_form')->toHtml(),
        ));	

        return parent::_beforeToHtml();
    }
}
