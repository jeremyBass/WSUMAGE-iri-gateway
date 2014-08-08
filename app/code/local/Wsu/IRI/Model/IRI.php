<?php

class Wsu_Iri_Model_Iri extends Mage_Core_Model_Abstract {
	public function _construct() {
        parent::_construct();
        $this->_init('iri/iri');
    }
}
