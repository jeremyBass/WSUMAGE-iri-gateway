<?php
class Wsu_IRI_Model_Mysql4_IRI_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    public function _construct() {
        parent::_construct();
        $this->_init('iri/iri');
    }
}