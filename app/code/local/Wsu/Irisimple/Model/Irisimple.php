<?php
class Wsu_Irisimple_Model_Irisimple extends Mage_Payment_Model_Method_Abstract {
	protected $_code = 'irisimple';
    protected $_formBlockType 	= 'irisimple/form_irisimple';
    protected $_infoBlockType 	= 'irisimple/info_irisimple';

/*
	protected $_isGateway               = false;
    protected $_canAuthorize            = true;//throws an error when false?  doesn't seem like this is anything but a trap
    protected $_canCapture              = true;
    protected $_canCapturePartial       = false;
    protected $_canRefund               = false;
    protected $_canVoid                 = false;
    protected $_canUseInternal          = false;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = false;
*/


	public function assignData($data) {
		if (!($data instanceof Varien_Object)) {
			$data = new Varien_Object($data);
		}
		$info = $this->getInfoInstance();
		$info->setIriZip($data->getIriZip())
			->setIriDep($data->getIriDep());
		return $this;
	}

	public function validate() {
		parent::validate();

		$info = $this->getInfoInstance();

		$zip = $info->getIriZip();
		$dep = $info->getIriDep();
		$errorMsg="";
		if(empty($zip) || empty($dep)){
			$errorCode = 'invalid_data';
			$errorMsg = $this->_getHelper()->__('Campus Zip and Department Information are required fields');
		}
		if($errorMsg!=""){
			Mage::throwException($errorMsg);
		}
		return $this;
	}
}
?>
