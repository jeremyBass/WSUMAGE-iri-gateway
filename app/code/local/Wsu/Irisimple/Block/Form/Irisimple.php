<?php
class Wsu_Irisimple_Block_Form_Irisimple extends Mage_Payment_Block_Form {
	protected function _construct() {
		parent::_construct();
		$this->setTemplate('irisimple/form/iri.phtml');
	}
}