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
class Tm_Offermanager_Adminhtml_OffermanagerController extends Mage_Adminhtml_Controller_action
{

	/**
     * Prepare layout for active links & breadcrumbs
     */
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('offermanager/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Offer Manager'), Mage::helper('adminhtml')->__('Offer Manager'));
		return $this;
	}   
 
	/**
     * Render layout for grid
     */
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	/**
     * Get data for edit particular offer
     */
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('offermanager/offermanager')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
			Mage::register('offermanager_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('offermanager/items');
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Offer Manager'), Mage::helper('adminhtml')->__('Offer Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Offer News'), Mage::helper('adminhtml')->__('Offer News'));
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()->createBlock('offermanager/adminhtml_offermanager_edit'))
				->_addLeft($this->getLayout()->createBlock('offermanager/adminhtml_offermanager_edit_tabs'));
			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('offermanager')->__('Offer does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	/**
     * Add new offer form
     */
	public function newAction() {
		$this->_forward('edit');
	}
 
	/**
     * Save data of add / edit offer
     */
	public function saveAction()
	{
		if ($data = $this->getRequest()->getPost()) {
			$flag=0;
			if (empty($data['offer_name']) || !isset($data['offer_name'])) {
				$flag = $flag+1;
			} elseif(empty($data['rule_id']) || !isset($data['rule_id'])) {
				$flag = $flag+1;
			}
			if ($flag != 0) {
				Mage::getSingleton('core/session')->addError('* field is necesarry to field.');
			} else {
				//if Banner image set.
				if (isset($_FILES['offer_image']['name']) && $_FILES['offer_image']['name'] != '') {
					try { 
						$uploader = new Varien_File_Uploader('offer_image');
						
						//Bellow extention would work
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
						$uploader->setAllowRenameFiles(true);
						$uploader->setFilesDispersion(false);
						//We set media as the upload dir
						$path = Mage::getBaseDir('media') . DS .'catalog'. DS. 'offer' . DS . 'banners' . DS ;
						$bannerImgName = preg_replace("/[^a-zA-Z0-9.-]/", "_",$_FILES['offer_image']['name']);
						$destFile = $path.$bannerImgName;
						$fileName = $uploader->getNewFileName($destFile);
						if (is_dir($path)) {
							$uploader->save($path, $fileName);
						} else {
							mkdir($path, 0777, true);
							$uploader->save($path, $fileName);
						}
						$data['offer_image'] = 'catalog/offer/banners/'.$fileName;
						
					} catch (Exception $e) {
						echo $e;exit;
					}
				} else {       
					if (isset($data['offer_image']['delete']) && $data['offer_image']['delete'] == 1) {
						$data['offer_image'] = '';
					} else {
						unset($data['offer_image']);
					}
				}
				//if icon image set.
				if (isset($_FILES['offer_icon']['name']) && $_FILES['offer_icon']['name'] != '')  {
					try {		
						$uploader = new Varien_File_Uploader('offer_icon');
						//Below extention would work
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
						$uploader->setAllowRenameFiles(true);
						$uploader->setFilesDispersion(false);
						$path = Mage::getBaseDir('media') . DS .'catalog'. DS. 'offer' . DS . 'icon' . DS ;
						$iconImgName = preg_replace("/[^a-zA-Z0-9.-]/", "_",$_FILES['offer_icon']['name']);
						$destFile = $path.$iconImgName;
						$fileName = $uploader->getNewFileName($destFile);
						if (is_dir($path)) {
							$uploader->save($path, $fileName);
						} else {
							mkdir($path,0777,true);
							$uploader->save($path, $fileName);
						}
						$data['offer_icon'] = 'catalog/offer/icon/'.$fileName;
						
					} catch (Exception $e) {
						echo $e;exit;
					}
				} else {       
					if (isset($data['offer_icon']['delete']) && $data['offer_icon']['delete'] == 1) {
						$data['offer_icon'] = '';
					} else {
						unset($data['offer_icon']);
					}
				}
					
				$model = Mage::getModel('offermanager/offermanager');		
				$model->setData($data)
					->setId($this->getRequest()->getParam('id'));
				$userId=Mage::getSingleton('admin/session')->getUser()->getId();
				try {
					if ($model->getCreatedDate == NULL) {
						$model->setCreatedDate(now());
					} 
					//make url_key if its not set, by removing '#$%&/' from string 
					if (empty($data['url_key'])) {
						$urlKey=preg_replace('/[^a-zA-Z0-9\']/', '-',$data['offer_name']);
					} else {
						$key = $data['url_key'];
						$urlKey = preg_replace('/[^a-zA-Z0-9\']/', '-',$key);
					}
					
					$model->setUrlKey($urlKey);
					$model->setUserId($userId);
					$model->save();
					
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('offermanager')->__('Offer was successfully saved'));
					Mage::getSingleton('adminhtml/session')->setFormData(false);
					
					// make id path and check its already exsit or not if exist then update it else make url rewrite for offer.
					$idPath='offer/id/'. $model->getOfferId();
					$rewriteModel = Mage::getModel('core/url_rewrite')->getCollection()
																	->addFieldToFilter('id_path',$idPath)
																	->getData();
					
					// if id path not found then add new 
					if(empty($rewriteModel)) {
						$storeId = Mage::app()->getStore()->getId();
						$urlRewrite = Mage::getModel('core/url_rewrite')
								->setIsSystem(0)
								->setStoreId($storeId)    
								->setIdPath('offer/id/'. $model->getOfferId())
								->setTargetPath('offermanager/offer/products/id/'.$model->getOfferId())
								->setRequestPath($urlKey.'.html');
						$urlRewrite->save();
					} else {
						//if id path exsist then update old one with current one 
						$id=$rewriteModel[0]['url_rewrite_id'];
						$rewriteUrl = Mage::getModel('core/url_rewrite')->load($id);
						$rewriteUrl->setTargetPath('offermanager/offer/products/id/'.$model->getOfferId())
								->setRequestPath($urlKey.'.html');	
						$rewriteUrl->save();	
					}
					
					if ($this->getRequest()->getParam('back')) {
						$this->_redirect('*/*/edit', array('id' => $model->getId()));
						return;
					}
					$this->_redirect('*/*/');
					return;	
				} catch (Exception $e) {
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					Mage::getSingleton('adminhtml/session')->setFormData($data);
					$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
					return;
				}
			}
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('offermanager')->__('Unable to find Offer to save'));
        $this->_redirect('*/*/');
	}

	/**
     * Delete offer
     */
	public function deleteAction() 
	{
		if($this->getRequest()->getParam('id') > 0) {
			$idPath = 'offer/id/'.$this->getRequest()->getParam('id');
			$urlModel = Mage::getModel('core/url_rewrite')->getCollection()
															->addFieldToFilter('id_path', $idPath)
															->getData();
			$rewriteId = $urlModel[0]['url_rewrite_id'];
			if (!empty($rewriteId) && isset($rewriteId)) {
				$rewriteModel = Mage::getModel('core/url_rewrite')->load($rewriteId);
				$rewriteModel->delete();
			}
			try {
				$model = Mage::getModel('offermanager/offermanager');
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Offer was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	/**
     * Delete multiple offers
     */
    public function massDeleteAction() 
	{
        $offermanagerIds = $this->getRequest()->getParam('offermanager');
        if(!is_array($offermanagerIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Offer(s)'));
        } else {
            try {
                foreach ($offermanagerIds as $offermanagerId) {
					$idPath='offer/id/'.$offermanagerId;
					$urlModel = Mage::getModel('core/url_rewrite')->getCollection()
																	->addFieldToFilter('id_path',$idPath)
																	->getData();
					$rewriteId = $urlModel[0]['url_rewrite_id'];
					if (!empty($rewriteId) && isset($rewriteId)) {
						$rewriteModel = Mage::getModel('core/url_rewrite')->load($rewriteId);
						$rewriteModel->delete();
					}
                    $offermanager = Mage::getModel('offermanager/offermanager')->load($offermanagerId);
                    $offermanager->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($offermanagerIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
	/**
     * Change status for multiple offers
     */
    public function massStatusAction()
    {
        $offermanagerIds = $this->getRequest()->getParam('offermanager');
        if (!is_array($offermanagerIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Offer(s)'));
        } else {
            try {
                foreach ($offermanagerIds as $offermanagerId) {
                    $offermanager = Mage::getSingleton('offermanager/offermanager')
                        ->load($offermanagerId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($offermanagerIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
	/**
     * Export csv
     */
    public function exportCsvAction()
    {
        $fileName   = 'offermanager.csv';
        $content    = $this->getLayout()->createBlock('offermanager/adminhtml_offermanager_grid')->getCsv();
        $this->_sendUploadResponse($fileName, $content);
    }

	/**
     * Export xml
     */
    public function exportXmlAction()
    {
        $fileName   = 'offermanager.xml';
        $content    = $this->getLayout()->createBlock('offermanager/adminhtml_offermanager_grid')->getXml();
        $this->_sendUploadResponse($fileName, $content);
    }

	/**
     * Response as a files 
     */
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}