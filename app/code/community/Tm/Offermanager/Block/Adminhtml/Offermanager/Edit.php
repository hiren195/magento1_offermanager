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
class Tm_Offermanager_Block_Adminhtml_Offermanager_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Add action buttons on form
     *
     */
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'offermanager';
        $this->_controller = 'adminhtml_offermanager';
        
        $this->_updateButton('save', 'label', Mage::helper('offermanager')->__('Save Offer'));
        $this->_updateButton('delete', 'label', Mage::helper('offermanager')->__('Delete Offer'));
        
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('offermanager_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'offermanager_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'offermanager_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
    * Getter for header text
    *
    * @return boolean
    */
    public function getHeaderText()
    {
        if (Mage::registry('offermanager_data') && Mage::registry('offermanager_data')->getId()) {
            return Mage::helper('offermanager')->__("Edit Offer '%s'", $this->htmlEscape(Mage::registry('offermanager_data')->getOfferName()));
        } else {
            return Mage::helper('offermanager')->__('Add Offer');
        }
    }
}
