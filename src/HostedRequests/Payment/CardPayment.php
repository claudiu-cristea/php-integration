<?php

require_once 'HostedPayment.php';
require_once  SVEA_REQUEST_DIR.'/Constant/PaymentMethod.php';

/**
 * Extends HostedPayment
 * Goes to PayPage and excludes all methods that are not cardpayments
 * @author Anneli Halld'n, Daniel Brolund for Svea Webpay
 * @package HostedRequests/Payment
 */
class CardPayment extends HostedPayment {

    /**
     * 
     * @param type $order
     */
    public function __construct($order) {
        parent::__construct($order);
    }

    protected function configureExcludedPaymentMethods($request) {
        $methods[] = PaymentMethod::PAYPAL;

        switch ($this->order->countryCode) {
            case "SE":
                $methods[] = PaymentMethod::DBNORDEASE;
                $methods[] = PaymentMethod::DBSEBSE;
                $methods[] = PaymentMethod::DBSEBFTGSE;
                $methods[] = PaymentMethod::DBSHBSE;
                $methods[] = PaymentMethod::DBSWEDBANKSE;
            default:
                break;
        }
        $exclude = new ExcludePayments();
        $methods = array_merge((array)$methods, (array)$exclude->excludeInvoicesAndPaymentPlan($this->order->countryCode));
        
        $request['excludePaymentMethods'] = $methods;
        return $request;
    }
    
    /**
     * Set return Url for redirect when payment is completed
     * @param type $returnUrlAsString
     * @return \HostedPayment
     */
    public function setReturnUrl($returnUrlAsString) {
        $this->returnUrl = $returnUrlAsString;
        return $this;
    }
    
    /**
     * 
     * @param type $cancelUrlAsString
     * @return \HostedPayment
     */
    public function setCancelUrl($cancelUrlAsString) {
        $this->cancelUrl = $cancelUrlAsString;
        return $this;
    }
}

?>