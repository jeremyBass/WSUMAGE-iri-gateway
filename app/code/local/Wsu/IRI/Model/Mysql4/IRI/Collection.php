<?php
class Wsu_Iri_Model_Mysql4_Iri_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    public function _construct() {
        parent::_construct();
        $this->_init('iri/iri');
    }
}