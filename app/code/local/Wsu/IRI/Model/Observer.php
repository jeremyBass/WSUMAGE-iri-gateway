<?php
class Wsu_IRI_Model_Observer {
    public function saveCredit(Varien_Event_Observer $observer) {
        $order               = $observer->getEvent()->getOrder();
        $order_id            = $observer->getEvent()->getOrder()->getId();
        $payment_method_code = $order->getPayment()->getMethodInstance()->getCode();
        if ($payment_method_code == 'iri') {
            $customerId      = $order->getCustomerId();
            $customer        = Mage::getModel('customer/customer')->load($customerId);
            $Availablelimit  = $customer->getCreditLimit();
            $AppliedCredit   = $order->getBaseGrandTotal();
            $setUpdatedlimit = $Availablelimit - $AppliedCredit;
            try {
                $model = Mage::getModel('iri/iri');
                $model->setOrderId($order_id);
                $model->setCustomerId($customerId);
                $model->setAppliedAmount($AppliedCredit);
                $model->setCreatedTime(now());
                $model->save();
                $customer->setCreditLimit($setUpdatedlimit);
                $customer->save();
            }
            catch (Exception $e) {
                Mage::getModel('core/session')->addError($e->getMessage());
            }
        }
    }
    public function revertCredit(Varien_Event_Observer $observer) {
        $creditMemo          = $observer->getEvent()->getCreditmemo();
        $order               = $creditMemo->getOrder();
        $order_id            = $order->getId();
        $payment_method_code = $order->getPayment()->getMethodInstance()->getCode();
        $customer            = Mage::getModel('customer/customer')->load($order->getCustomerId());
        if ($payment_method_code == 'iri') {
            $Availablelimit       = $customer->getCreditLimit();
            $Creditcollection     = Mage::getModel('iri/iri')->getCollection()->addFieldToFilter('customer_id', $customerId)->addFieldToFilter('order_id', $order_id)->getFirstItem();
            $customerUpdatedlimit = $order->getBaseGrandTotal();
            $setlimit             = $Availablelimit + $customerUpdatedlimit;
            $customer->setCreditLimit($setlimit);
            $customer->save();
        }
    }
    public function isAvailable(Varien_Event_Observer $observer) {
        $event  = $observer->getEvent();
        $method = $event->getMethodInstance();
        $result = $event->getResult();
        $quote  = $event->getQuote();
        if ($method->getCode() == 'iri' && $quote!=null) {
            $customerGroup               = $quote->getCustomerGroupId();
            $SelectedCustomerGroups      = Mage::getStoreConfig('payment/iri/specificcustomers');
            $SelectedCustomerGroupsArray = explode(",", $SelectedCustomerGroups);
            if ($SelectedCustomerGroups != "" || $quote->getCustomerId() == " ") {
                if (!in_array($customerGroup, $SelectedCustomerGroupsArray)) {
                    $result->isAvailable = false;
                }
            } else {
                if ($result->isAvailable == 1) {
                    $result->isAvailable = true;
                }
            }
        }
    }
    public function implementOrderStatus($event) {
        $order       = $event->getOrder();
        $Orderstatus = Mage::getStoreConfig('payment/iri/order_status');
        if ($this->_getPaymentMethod($order) == 'iri' && $Orderstatus == "processing") {
            if ($order->canInvoice())
                $this->_processOrderStatus($order);
        }
        return $this;
    }
    private function _getPaymentMethod($order) {
        return $order->getPayment()->getMethodInstance()->getCode();
    }
    private function _processOrderStatus($order) {
        $invoice = $order->prepareInvoice();
        $invoice->register();
        Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder())->save();
        $invoice->sendEmail(true, '');
        return true;
    }
    public function saveMultiCredit(Varien_Event_Observer $observer) {
        $orders      = $observer->getEvent()->getOrders();
        $Orderstatus = Mage::getStoreConfig('payment/iri/order_status');
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $payment_method_code = $order->getPayment()->getMethodInstance()->getCode();
                if ($payment_method_code == 'iri') {
                    $order_id        = $order->getId();
                    $customerId      = $order->getCustomerId();
                    $customer        = Mage::getModel('customer/customer')->load($customerId);
                    $Availablelimit  = $customer->getCreditLimit();
                    $AppliedCredit   = $order->getBaseGrandTotal();
                    $setUpdatedlimit = $Availablelimit - $AppliedCredit;
                    try {
                        $model = Mage::getModel('iri/iri');
                        $model->setOrderId($order_id);
                        $model->setCustomerId($customerId);
                        $model->setAppliedAmount($AppliedCredit);
                        $model->setCreatedTime(now());
                        $model->save();
                        $customer->setCreditLimit($setUpdatedlimit);
                        $customer->save();
                        if ($order->canInvoice() && $Orderstatus == "processing") {
                            $invoice = $order->prepareInvoice();
                            $invoice->register();
                            Mage::getModel('core/resource_transaction')->addObject($invoice)->addObject($invoice->getOrder())->save();
                            $invoice->sendEmail(true, '');
                        }
                    }
                    catch (Exception $e) {
                        Mage::getModel('core/session')->addError($e->getMessage());
                    }
                }
            }
        }
    }
}