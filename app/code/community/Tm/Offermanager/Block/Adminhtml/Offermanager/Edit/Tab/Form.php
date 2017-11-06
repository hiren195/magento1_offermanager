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
class Tm_Offermanager_Block_Adminhtml_Offermanager_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form for offermanager add/editing.
     *
     * @param void
     * @return Mage_Adminhtml_Block_Offermanager_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('offermanager_form', array('legend'=>Mage::helper('offermanager')->__('Offer information')));

        $fieldset->addField('offer_name', 'text', array(
            'label'     => Mage::helper('offermanager')->__('Offer Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'offer_name',
        ));

        $fieldset->addField('description', 'textarea', array(
            'label'     => Mage::helper('offermanager')->__('Description'),
            'required'  => false,
            'name'      => 'description',
        ));

        $fieldset->addField('offer_image', 'image', array(
            'label'     => Mage::helper('offermanager')->__('Offer Banner'),
            'required'  => false,
            'name'      => 'offer_image',
			'note'      => 'Allowed image extension jpg, jpeg, gif, png'
        ));


        $fieldset->addField('offer_icon', 'image', array(
            'label'     => Mage::helper('offermanager')->__('Offer Icon'),
	        'required'	=> false,
	        'name'		=>'offer_icon',
            'note'      => 'Allowed image extension jpg, jpeg, gif, png'
        ));

        $rules = Mage::getSingleton('offermanager/rules')->getOptionArray();

        $fieldset->addField('rule_id', 'select', array(
            'label'     => Mage::helper('offermanager')->__('Select Rule'),
            'name'      => 'rule_id',
            'values'    => $rules,
            'class'     => 'required-entry',
            'required'	=> true,
        ));

        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('offermanager')->__('Status'),
            'name'      => 'status',
            'values'    => array(
	            array(
		            'value'     => 1,
		            'label'     => Mage::helper('offermanager')->__('Enabled'),
	            ),
	            array(
		            'value'     => 2,
		            'label'     => Mage::helper('offermanager')->__('Disabled'),
	            ),
            ),
        ));

        $fieldset->addField('url_key', 'text', array(
            'label'     => Mage::helper('offermanager')->__('Url Key'),
            'name'      => 'url_key',
            'required'	=> false,
        ));

        if ( Mage::getSingleton('adminhtml/session')->getOffermanagerData() ) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getOffermanagerData());
            Mage::getSingleton('adminhtml/session')->setOffermanagerData(null);
        } elseif ( Mage::registry('offermanager_data') ) {
            $form->setValues(Mage::registry('offermanager_data')->getData());
        }
        return parent::_prepareForm();
    }
}
