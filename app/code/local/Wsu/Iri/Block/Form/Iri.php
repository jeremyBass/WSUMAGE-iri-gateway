<?php
class Wsu_Iri_Block_Form_Iri extends Mage_Payment_Block_Form {
	protected function _construct() {
		parent::_construct();
		$this->setTemplate('iri/form/iri.phtml');
	}

}
