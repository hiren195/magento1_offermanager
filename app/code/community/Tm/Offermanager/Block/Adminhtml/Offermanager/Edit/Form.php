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
class Tm_Offermanager_Block_Adminhtml_Offermanager_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
    * Prepare form
    *
    */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                      'method' => 'post',
                                      'enctype' => 'multipart/form-data'
                                    )
                                );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
