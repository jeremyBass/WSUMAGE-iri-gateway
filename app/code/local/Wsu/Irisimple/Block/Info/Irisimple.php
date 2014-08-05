<?php
class Wsu_Irisimple_Block_Info_Pay extends Mage_Payment_Block_Info {
	protected function _prepareSpecificInformation($transport = null) {
		if (null !== $this->_paymentSpecificInformation) {
			return $this->_paymentSpecificInformation;
		}
		$info = $this->getInfo();
		$transport = new Varien_Object();
		$transport = parent::_prepareSpecificInformation($transport);
		$transport->addData(array(
			Mage::helper('payment')->__('Campus Zip') => $info->getIriZip(),
			Mage::helper('payment')->__('Department Information') => $info->getIriDep()
		));
		return $transport;
	}
}