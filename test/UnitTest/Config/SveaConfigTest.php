<?php

$root = realpath(dirname(__FILE__));
require_once $root . '/../../../src/Includes.php';

    

/**
 * Description of SveaConfigTest
 */
class SveaConfigTest extends PHPUnit_Framework_TestCase {
    
    function t_estInstancesOfSveaConfig(){
        $obj1 = SveaConfig::getConfig();
        $obj2 = SveaConfig::getConfig();
        $this->assertEquals($obj1->password, $obj2->password);
        
        $obj1->password = "Hej";
        $this->assertNotEquals($obj1->password, $obj2->password);
    }
    
    function t_estSetTestmode(){
        $conf = SveaConfig::setConfig()
                ->setMerchantId()
                ->setSecretProd()
                ->setSecretTest()
                ->setPassword()
                ->setUsername()
                ->setClientNumberInvoice()
                ->setClientNumberPaymentPlan()
                //->setTestmode()()
                ->setAlternativeUrl(); //overwrite all urls
        
        $request = WebPay::createOrder($conf)
            ->addOrderRow(Item::orderRow()
                ->setArticleNumber(1)
                ->setQuantity(2)
                ->setAmountExVat(100.00)
                ->setDescription("Specification")
                ->setName('Prod')
                ->setUnit("st")
                ->setVatPercent(25)
                ->setDiscountPercent(0)
                )
            ->addCustomerDetails(Item::individualCustomer()->setNationalIdNumber(194605092222))
                    ->setCountryCode("SE")
                    ->setCustomerReference("33")
                    ->setOrderDate("2012-12-12")
                    ->setCurrency("SEK")
                    ->useInvoicePayment()// returnerar InvoiceOrder object 
                       // ->setPasswordBasedAuthorization("sverigetest", "sverigetest", 79021)
                        ->prepareRequest();
    }
    
    
        function testOrderWithNoConfigFromFunction(){
           $request = WebPay::createOrder(SveaConfig::getTestConfig())
            ->addOrderRow(Item::orderRow()
                ->setArticleNumber(1)
                ->setQuantity(2)
                ->setAmountExVat(100.00)
                ->setDescription("Specification")
                ->setName('Prod')
                ->setVatPercent(25)
                )
            ->addCustomerDetails(Item::individualCustomer()->setNationalIdNumber(194605092222))
                    ->setCountryCode("NO")
                    ->setCustomerReference("33")
                    ->setOrderDate("2012-12-12")
                    ->setCurrency("SEK")
                    ->useInvoicePayment()// returnerar InvoiceOrder object 
                        ->prepareRequest();
              
            $this->assertEquals("webpay_test_no", $request->request->Auth->Username);
            $this->assertEquals("dvn349hvs9+29hvs", $request->request->Auth->Password);
            $this->assertEquals(32666, $request->request->Auth->ClientNumber);
    }
}

?>
