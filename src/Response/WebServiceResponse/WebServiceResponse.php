<?php
require_once SVEA_REQUEST_DIR . '/Includes.php';
/**
 * Description of WebServiceResponse
 *
 * @author Anneli Halld'n, Daniel Brolund for Svea Webpay
 */
class WebServiceResponse {
  
    public $accepted;
    public $resultcode;
    public $paymentmethod;
    public $merchantId;

    function __construct($message) {
        $this->formatObject($message);
    }
    

}

?>
