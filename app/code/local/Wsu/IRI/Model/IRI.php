<?php

class Wsu_IRI_Model_IRI extends Mage_Core_Model_Abstract {
	public function _construct() {
        parent::_construct();
        $this->_init('iri/iri');
    }
}
