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
class Tm_Offermanager_Model_Observer 
{
	/**
     * Add offers in top menu
	 *
	 * @return object
     */
	public function hookToMenuPreDispatch($observer)
	{
		$menu = $observer->getMenu();
        $tree = $menu->getTree();
        $action = Mage::app()->getFrontController()->getAction()->getFullActionName();

        $nodeId = 'offer-node';
        $data = array(
            'name' => Mage::helper('offermanager')->__('Offers'),
            'id' => $nodeId,
            'url' => Mage::getUrl('offermanager/offer/viewalloffers'),
            'is_active' => ($action == 'offermanager_offer_viewalloffers')
        );
		
        $node = new Varien_Data_Tree_Node($data, 'id', $tree, $menu);
        $menu->addChild($node);
		
        $offerModel = Mage::getModel('offermanager/offermanager')->getCollection()
													->AddFieldToFilter('status','1')
													->getData();
		$activeOffers=Mage::getResourceModel('offermanager/offermanager')->getRuleStatus($offerModel);
	
		foreach ($activeOffers as $activeOffer) {
			$tree = $node->getTree();
			$data = array(
				'name'   => $activeOffer['offer_name'],
				'id'     => 'offer-node-'.$activeOffer['offer_id'],
				'url'    =>  Mage::getUrl('/').$activeOffer['url_key'].'.html',
			);	 
			$subNode = new Varien_Data_Tree_Node($data, 'id', $tree, $node);
			$node->addChild($subNode);
		}
        return $this;
	}
}