<?php
class Wsu_IRI_Block_Form_IRI extends Mage_Irisimplement_Block_Form {
	protected function _construct() {
		parent::_construct();
		$this->setTemplate('iri/form/iri.phtml');
	}

}
