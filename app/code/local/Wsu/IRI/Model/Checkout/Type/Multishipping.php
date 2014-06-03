<?php

class Wsu_IRI_Model_Checkout_Type_Multishipping extends Mage_Checkout_Model_Type_Multishipping {
    public function setPaymentMethod($payment) {
        if (!isset($payment['method'])) {
            Mage::throwException(Mage::helper('checkout')->__('Payment method is not defined'));
        }
        $quote           = $this->getQuote();
        $_totalData      = $quote->getData();
        $_grand          = $_totalData['base_grand_total'];
        $customerSession = $this->getCustomerSession();
        $customer        = $customerSession->getCustomer();
        $creditLimit     = $customer->getCreditLimit();
        if ($payment['method'] == "iri" && $creditLimit < $_grand) {
			$limit_message=Mage::getStoreConfig('payment/iri/limit_message');
            Mage::throwException( ( $limit_message!="" ? $limit_message : Mage::helper('checkout')->__("You don't have sufficient credit.") ) );
        }
        $quote->getPayment()->importData($payment);
        // shipping totals may be affected by payment method
        if (!$quote->isVirtual() && $quote->getShippingAddress()) {
            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote->setTotalsCollectedFlag(false)->collectTotals();
        }
        $quote->save();
        return $this;
    }
}
