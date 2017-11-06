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
class Tm_Offermanager_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
     * Function for search value from multi Array ,And its return path to reach that value.
	 *
	 * @return array
     */
	public function recursiveSearchArray( $needle, $haystack, $strict=false, $path=array() ) 
	{ 
		if (!is_array($haystack)) { 
			return false; 
		} 
	 
		foreach ($haystack as $key => $val) { 
			if (is_array($val) && $subPath = $this->recursiveSearchArray($needle, $val, $strict, $path)) { 
				$path = array_merge($path, array($key), $subPath); 
				return $path; 
			} elseif((!$strict && $val == $needle) || ($strict && $val === $needle)) { 
				$path[] = $key; 
				return $path; 
			} 
		} 
		return false;  
	}
	
    /**
     * Image resize function
	 *
	 * @return array
     */	
	public function resizeImg($fileNameDir, $filepath, $width, $height = '')
    {
		$fileName = substr(strrchr($fileNameDir, "/"), 1);
		$fileNameStr = explode($fileName,$fileNameDir);
		$filepath = str_replace('/', DS, $fileNameStr[0]);
		
        $folderURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$fileNameStr[0];
        $imageURL = $folderURL . $fileName;

        $basePath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $filepath . $fileName;
		$resizeFlag = '';
		if (!empty($width) && !empty($height)) {
			$resizeFlag = $width.'x'.$height;
		} else {
			$resizeFlag = $width.'x'.$width;
		}
        $newPath = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . $filepath . DS . "resized" . DS . $resizeFlag . DS . $fileName;
        //if width empty then return original size image's URL
        if ($width != '') {
            //if image has already resized then just return URL
            if (file_exists($basePath) && is_file($basePath) && !file_exists($newPath)) {
                $imageObj = new Varien_Image($basePath);
                $imageObj->constrainOnly(false);
                $imageObj->keepAspectRatio(true);
                $imageObj->keepFrame(true);
                $imageObj->backgroundColor(array(255,255,255));
                $imageObj->resize($width, $height);
                $imageObj->save($newPath);
            }
            $resizedURL = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $fileNameStr[0] . "resized/". $resizeFlag . '/' . $fileName;
        } else {
            $resizedURL = $imageURL;
        }
        return $resizedURL;
    }
}
