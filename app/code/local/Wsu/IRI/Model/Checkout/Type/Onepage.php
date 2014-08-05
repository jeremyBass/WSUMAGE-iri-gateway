<?php
class Wsu_IRI_Model_Checkout_Type_Onepage extends Mage_Checkout_Model_Type_Onepage {
    public function saveIrisimplement($data) {
        if (empty($data)) {
            return array(
                'error' => -1,
                'message' => Mage::helper('checkout')->__('Invalid data.')
            );
        }
        $quote           = $this->getQuote();
        $_totalData      = $quote->getData();
        $_grand          = $_totalData['base_grand_total'];
        $customerSession = $this->getCustomerSession();
        $customer        = $customerSession->getCustomer();
        $creditLimit     = $customer->getCreditLimit();
        if ($data['method'] == "iri" && $creditLimit < $_grand) {
			$limit_message=Mage::getStoreConfig('payment/iri/limit_message');
            Mage::throwException( ( $limit_message!="" ? $limit_message : Mage::helper('checkout')->__("You don't have sufficient credit.") ) );
        }
        if ($quote->isVirtual()) {
            $quote->getBillingAddress()->setIrisimplementMethod(isset($data['method']) ? $data['method'] : null);
        } else {
            $quote->getShippingAddress()->setIrisimplementMethod(isset($data['method']) ? $data['method'] : null);
        }
        // shipping totals may be affected by payment method
        if (!$quote->isVirtual() && $quote->getShippingAddress()) {
            $quote->getShippingAddress()->setCollectShippingRates(true);
        }
        $payment = $quote->getIrisimplement();
        $payment->importData($data);
        $quote->save();
        $this->getCheckout()->setStepData('payment', 'complete', true)->setStepData('review', 'allow', true);
        return array();
    }
}
