<?php
class Wsu_Irisimple_Model_Irisimple extends Mage_Payment_Model_Method_Abstract {
	protected $_code = 'irisimple';
	protected $_formBlockType = 'irisimple/form_irisimple';
	protected $_infoBlockType = 'irisimple/info_irisimple';

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
		if(empty($zip) || empty($dep)){
			$errorCode = 'invalid_data';
			$errorMsg = $this->_getHelper()->__('Campus Zip and Department Information are required fields');
		}
		if($errorMsg){
			Mage::throwException($errorMsg);
		}
		return $this;
	}
}
?>
