<?php
class Wsu_IRI_Model_Method_IRI extends Mage_Payment_Model_Method_Abstract {
    protected $_code = 'iri';
    /*protected $_isInitializeNeeded = true;
    protected $_canUseInternal = true;
    protected $_canUseForMultishipping = true;
	
    protected $_isGateway               = false;
	protected $_canUseCheckout          = true;
	protected $_formBlockType = 'iri/form_iri';*/
    //protected $_infoBlockType = 'iri/info_iri';
	
	public function assignData($data){
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        $info->setCheckNo($data->getCheckNo())->setCheckDate($data->getCheckDate());
        return $this;
    }

    public function validate(){
        parent::validate();
        $info = $this->getInfoInstance();

        $no = $info->getCheckNo();
        $date = $info->getCheckDate();
        if(empty($no) || empty($date)){
            $errorCode = 'invalid_data';
            $errorMsg = $this->_getHelper()->__('Check No and Date are required fields');
        }
        if($errorMsg){
            Mage::throwException($errorMsg);
        }
        return $this;
    }
}
