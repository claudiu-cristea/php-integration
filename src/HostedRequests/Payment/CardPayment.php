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
    
    public $langCode = 'en';

    /**
     * 
     * @param type $order
     */
    public function __construct($order) {
        parent::__construct($order);
    }

    protected function configureExcludedPaymentMethods($request) {       
        //directbanks      
        $methods[] = SystemPaymentMethod::BANKAXESS;
        $methods[] = SystemPaymentMethod::DBNORDEASE;
        $methods[] = SystemPaymentMethod::DBSEBSE;
        $methods[] = SystemPaymentMethod::DBSEBFTGSE;
        $methods[] = SystemPaymentMethod::DBSHBSE;
        $methods[] = SystemPaymentMethod::DBSWEDBANKSE;
        //other        
       $methods[] = SystemPaymentMethod::PAYPAL;       
         
        $exclude = new ExcludePayments();
        $methods = array_merge((array)$methods, (array)$exclude->excludeInvoicesAndPaymentPlan($this->order->countryCode));
        
        $request['excludePaymentMethods'] = $methods;
        return $request;
    }
    
    /**
     * Alternative drop or change file in Config/SveaConfig.php
     * Note! This fuction may change in future updates.
     * @param type $merchantId
     * @param type $secret
     * @return \HostedPayment
     
    public function setMerchantIdBasedAuthorization($merchantId,$secret){
        $this->order->conf->merchantId = $merchantId;
        $this->order->conf->secret = $secret;
        return $this;
    }
     * 
     */
    
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
    
     public function setPayPageLanguage($languageCodeAsISO639){
        switch ($languageCodeAsISO639) {
            case "sv":
                $this->langCode = $languageCodeAsISO639;

                break;
            case "en":
                $this->langCode = $languageCodeAsISO639;

                break;
            case "da":
                $this->langCode = $languageCodeAsISO639;

                break;
            case "fi":
                $this->langCode = $languageCodeAsISO639;

                break;
            case "no":
                $this->langCode = $languageCodeAsISO639;

                break;
            case "de":
                $this->langCode = $languageCodeAsISO639;

                break;
            case "es":
                $this->langCode = $languageCodeAsISO639;

                break;
            case "fr":
                $this->langCode = $languageCodeAsISO639;

                break;
            case "it":
                $this->langCode = $languageCodeAsISO639;

                break;     
            case "nl":
                $this->langCode = $languageCodeAsISO639;

                break;     
            default:
                 $this->langCode = "en";
                break;
        }
        
        return $this;
    }
}

?>
