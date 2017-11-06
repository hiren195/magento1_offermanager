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
class Tm_Offermanager_Block_Adminhtml_Offermanager_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Constructor
     *
     * Set main configuration of grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('offermanagerGrid');
        $this->setDefaultSort('offer_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection for grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('offermanager/offermanager')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
    * Prepare columns for grid
    */
    protected function _prepareColumns()
    {
        $this->addColumn('offer_id', array(
            'header'    => Mage::helper('offermanager')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'offer_id',
        ));

        $this->addColumn('offer_name', array(
            'header'    => Mage::helper('offermanager')->__('Offer Name'),
            'align'     =>'left',
            'index'     => 'offer_name',
        ));


        $this->addColumn('created_date', array(
            'header'    => Mage::helper('offermanager')->__('Created at'),
            'width'     => '150px',
	        'index'     => 'created_date',
        ));


        $this->addColumn('status', array(
            'header'    => Mage::helper('offermanager')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
	                        1 => 'Enabled',
	                        2 => 'Disabled',
                        ),
        ));

        $this->addColumn('action', array(
            'header'    =>  Mage::helper('offermanager')->__('Action'),
            'width'     => '100',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   =>  array(
	                            array(
		                            'caption'   => Mage::helper('offermanager')->__('Edit'),
		                            'url'       => array('base'=> '*/*/edit'),
		                            'field'     => 'id'
	                            )
                            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('offermanager')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('offermanager')->__('XML'));
        return parent::_prepareColumns();
    }
	
    /**
    * Prepare mass action for grid
    */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('offer_id');
        $this->getMassactionBlock()->setFormFieldName('offermanager');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('offermanager')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('offermanager')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('offermanager/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('offermanager')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'visibility' => array(
                                'name' => 'status',
                                'type' => 'select',
                                'class' => 'required-entry',
                                'label' => Mage::helper('offermanager')->__('Status'),
                                'values' => $statuses
                            )
                        )
        ));
        return $this;
    }

    /**
    * Get edit row url for grid
    */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
