<?php

$root = realpath(dirname(__FILE__));
require_once $root . '/../../../src/Includes.php';
require_once $root . '/../../../src/WebServiceRequests/svea_soap/SveaSoapConfig.php';
require_once $root . '/../VoidValidator.php';

$root = realpath(dirname(__FILE__));
require_once $root . '/TestRowFactory.php';

/**
 * All functions named test...() will run as tests in PHP-unit framework
 * @author Anneli Halld'n, Daniel Brolund for Svea Webpay
 */
class NewOrderBuilderTest extends PHPUnit_Framework_TestCase {
    
    function testNewInvoiceOrderWithOrderRow(){       
        $request = WebPay::createOrder();
            ////->setTestmode()()();
        //foreach...
        $request = $request
            ->addOrderRow(
                Item::orderRow()
                    ->setArticleNumber(1)
                    ->setQuantity(2)
                    ->setAmountExVat(100.00)
                    ->setDescription("Specification")
                    ->setName('Prod')
                    ->setUnit("st")
                    ->setVatPercent(25)
                    ->setDiscountPercent(0)
                    );
        //end foreach
            $request = $request
                ->addCustomerDetails(Item::individualCustomer()->setNationalIdNumber(194605092222))
                ->setCountryCode("SE")
                ->setCustomerReference("33")
                ->setOrderDate("2012-12-12")
                ->setCurrency("SEK")
                ->useInvoicePayment()// returnerar InvoiceOrder object 
                    ->prepareRequest();
            
            $this->assertEquals(194605092222, $request->request->CreateOrderInformation->CustomerIdentity->NationalIdNumber); //Check all in identity
            $this->assertEquals(1, $request->request->CreateOrderInformation->OrderRows['OrderRow'][0]->ArticleNumber);
            $this->assertEquals(2, $request->request->CreateOrderInformation->OrderRows['OrderRow'][0]->NumberOfUnits);
            $this->assertEquals(100.00, $request->request->CreateOrderInformation->OrderRows['OrderRow'][0]->PricePerUnit);
            $this->assertEquals("Prod: Specification", $request->request->CreateOrderInformation->OrderRows['OrderRow'][0]->Description);
            $this->assertEquals("st", $request->request->CreateOrderInformation->OrderRows['OrderRow'][0]->Unit);
            $this->assertEquals(25, $request->request->CreateOrderInformation->OrderRows['OrderRow'][0]->VatPercent);
            $this->assertEquals(0, $request->request->CreateOrderInformation->OrderRows['OrderRow'][0]->DiscountPercent);

        }
    
        
        function testNewInvoiceOrderWithArray(){
     
        $orderRows[] = Item::orderrow()
                    ->setArticleNumber(1)
                    ->setQuantity(2)
                    ->setAmountExVat(100.00)
                    ->setDescription("Specification")
                    ->setName('Prod')
                    ->setUnit("st")
                    ->setVatPercent(25)
                    ->setDiscountPercent(0);
        $orderRows[] = Item::orderrow()
                    ->setArticleNumber(2)
                    ->setQuantity(2)
                    ->setAmountExVat(110.00)
                    ->setDescription("Specification")
                    ->setName('Prod')
                    ->setUnit("st")
                    ->setVatPercent(25)
                    ->setDiscountPercent(0);
        
        
        $request = WebPay::createOrder()
            //->setTestmode()()
            ->addOrderRow($orderRows)
            ->addCustomerDetails(Item::individualCustomer()->setNationalIdNumber(194605092222))
            ->setCountryCode("SE")
            ->setCustomerReference("33")
            ->setOrderDate("2012-12-12")
            ->setCurrency("SEK")
            ->useInvoicePayment()// returnerar InvoiceOrder object 
                ->prepareRequest();
        
            $this->assertEquals(194605092222, $request->request->CreateOrderInformation->CustomerIdentity->NationalIdNumber); //Check all in identity

       
        }
        
        function testOrderWithShippingFee(){
            $request = WebPay::createOrder();
            //->setTestmode()();
        //foreach...
        $request = $request
            ->addOrderRow(
                Item::orderRow()
                    ->setArticleNumber(1)
                    ->setQuantity(2)
                    ->setAmountExVat(100.00)
                    ->setDescription("Specification")
                    ->setName('Prod')
                    ->setUnit("st")
                    ->setVatPercent(25)
                    ->setDiscountPercent(0)
                    )
                ->addFee(Item::shippingFee()
                        ->setShippingId(1)
                        ->setName('shipping')
                        ->setDescription("Specification")
                        ->setAmountExVat(50)
                        ->setUnit("st")
                        ->setVatPercent(25)
                        ->setDiscountPercent(0)
                        );
        //end foreach
            $request = $request  
            ->addCustomerDetails(Item::individualCustomer()->setNationalIdNumber(194605092222))
            ->setCountryCode("SE")
            ->setCustomerReference("33")
            ->setOrderDate("2012-12-12")
            ->setCurrency("SEK")
            ->useInvoicePayment()// returnerar InvoiceOrder object 
                ->prepareRequest();
            
            $this->assertEquals(1, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->ArticleNumber);
            $this->assertEquals(1, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->NumberOfUnits);
            $this->assertEquals(50.00, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->PricePerUnit);
            $this->assertEquals("shipping: Specification", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->Description);
            $this->assertEquals("st", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->Unit);
            $this->assertEquals(25, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->VatPercent);
            $this->assertEquals(0, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->DiscountPercent);
        }
        
    function testOrderWithInvoiceFee(){     
        $request = WebPay::createOrder();
            //->setTestmode()();
        //foreach...
        $request = $request
            ->addOrderRow(
                Item::orderRow()
                    ->setArticleNumber(1)
                    ->setQuantity(2)
                    ->setAmountExVat(100.00)
                    ->setDescription("Specification")
                    ->setName('Prod')
                    ->setUnit("st")
                    ->setVatPercent(25)
                    ->setDiscountPercent(0)
                    )
                ->addFee(Item::invoiceFee()
                    ->setName('Svea fee')
                    ->setDescription("Fee for invoice")
                    ->setAmountExVat(50)
                    ->setUnit("st")
                    ->setVatPercent(25)
                    ->setDiscountPercent(0)
                        );
        //end foreach
            $request = $request  
            ->addCustomerDetails(Item::individualCustomer()->setNationalIdNumber(194605092222))
            ->setCountryCode("SE")
            ->setCustomerReference("33")
            ->setOrderDate("2012-12-12")
            ->setCurrency("SEK")
            ->useInvoicePayment()// returnerar InvoiceOrder object 
                ->prepareRequest();
            
            $this->assertEquals("", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->ArticleNumber);
            $this->assertEquals(1, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->NumberOfUnits);
            $this->assertEquals(50.00, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->PricePerUnit);
            $this->assertEquals("Svea fee: Fee for invoice", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->Description);
            $this->assertEquals("st", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->Unit);
            $this->assertEquals(25, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->VatPercent);
            $this->assertEquals(0, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->DiscountPercent);
        }
    function testOrderWithFixedDiscount(){     
        $request = WebPay::createOrder();
            //->setTestmode()();
        //foreach...
        $request = $request
            ->addOrderRow(
                Item::orderRow()
                    ->setArticleNumber(1)
                    ->setQuantity(2)
                    ->setAmountExVat(100.00)
                    ->setDescription("Specification")
                    ->setName('Prod')
                    ->setUnit("st")
                    ->setVatPercent(25)
                    ->setDiscountPercent(0)
                    )
                ->addDiscount(Item::fixedDiscount()
                   ->setDiscountId("1")
                    ->setAmountIncVat(100.00)
                    ->setUnit("st")
                    ->setDescription("FixedDiscount")
                    ->setName("Fixed")
                        );
        //end foreach
            $request = $request 
            ->addCustomerDetails(Item::individualCustomer()->setNationalIdNumber(194605092222))
            ->setCountryCode("SE")
            ->setCustomerReference("33")
            ->setOrderDate("2012-12-12")
            ->setCurrency("SEK")
            ->useInvoicePayment()// returnerar InvoiceOrder object 
                ->prepareRequest();
            
            $this->assertEquals("1", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->ArticleNumber);
            $this->assertEquals(1, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->NumberOfUnits);
            $this->assertEquals(-80.00, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->PricePerUnit);
            $this->assertEquals("Fixed: FixedDiscount", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->Description);
            $this->assertEquals("st", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->Unit);
            $this->assertEquals(25, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->VatPercent);
            $this->assertEquals(0, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->DiscountPercent);
        }
    function testOrderWithRelativeDiscount(){     
        $request = WebPay::createOrder();
            //->setTestmode()();
        //foreach...
        $request = $request
            ->addOrderRow(
                Item::orderRow()
                    ->setArticleNumber(1)
                    ->setQuantity(2)
                    ->setAmountExVat(100.00)
                    ->setDescription("Specification")
                    ->setName('Prod')
                    ->setUnit("st")
                    ->setVatPercent(25)
                    ->setDiscountPercent(0))
                ->addDiscount(
                Item::relativeDiscount()
                        ->setDiscountId("1")
                        ->setDiscountPercent(50)
                        ->setUnit("st")
                        ->setName('Relative')
                        ->setDescription("RelativeDiscount")
                        );                    
        //end foreach
            $request = $request  
            ->addCustomerDetails(Item::individualCustomer()->setNationalIdNumber(194605092222))
            ->setCountryCode("SE")
            ->setCustomerReference("33")
            ->setOrderDate("2012-12-12")
            ->setCurrency("SEK")
            ->useInvoicePayment()// returnerar InvoiceOrder object 
                ->prepareRequest();
            
            $this->assertEquals("1", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->ArticleNumber);
            $this->assertEquals(1, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->NumberOfUnits);
            $this->assertEquals(-50.00, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->PricePerUnit);
            $this->assertEquals("Relative: RelativeDiscount", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->Description);
            $this->assertEquals("st", $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->Unit);
            $this->assertEquals(25, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->VatPercent);
            $this->assertEquals(0, $request->request->CreateOrderInformation->OrderRows['OrderRow'][1]->DiscountPercent);
        }
        
        public function testBuildOrderWithIndividualCustomer(){
             $request = WebPay::createOrder();
            //->setTestmode()();
        //foreach...
            $request = $request
            ->addOrderRow(
                Item::orderRow()
                    ->setArticleNumber(1)
                    ->setQuantity(2)
                    ->setAmountExVat(100.00)
                    ->setDescription("Specification")
                    ->setName('Prod')
                    ->setUnit("st")
                    ->setVatPercent(25)
                    ->setDiscountPercent(0))
                ->addCustomerDetails(Item::individualCustomer()
                    ->setNationalIdNumber(194605092222)
                    ->setInitials("SB")
                    ->setBirthDate(1923, 12, 12)
                    ->setName("Tess", "Testson")
                    ->setEmail("test@svea.com")
                    ->setPhoneNumber(999999)
                    ->setIpAddress("123.123.123")
                    ->setStreetAddress("Gatan", 23)
                    ->setCoAddress("c/o Eriksson")
                    ->setZipCode(9999)
                    ->setLocality("Stan")
                       );                    
        //end foreach
            $request = $request
            ->setCountryCode("SE")
            ->setCustomerReference("33")
            ->setOrderDate("2012-12-12")
            ->setCurrency("SEK")
            ->useInvoicePayment()// returnerar InvoiceOrder object 
                ->prepareRequest();
        
        
            $this->assertEquals(194605092222, $request->request->CreateOrderInformation->CustomerIdentity->NationalIdNumber);
            $this->assertEquals(999999, $request->request->CreateOrderInformation->CustomerIdentity->PhoneNumber);
            $this->assertEquals("Gatan", $request->request->CreateOrderInformation->CustomerIdentity->Street);
            $this->assertEquals(23, $request->request->CreateOrderInformation->CustomerIdentity->HouseNumber);
            $this->assertEquals(9999, $request->request->CreateOrderInformation->CustomerIdentity->ZipCode);
            $this->assertEquals("Stan", $request->request->CreateOrderInformation->CustomerIdentity->Locality);
            $this->assertEquals("Individual", $request->request->CreateOrderInformation->CustomerIdentity->CustomerType);
        }
        
        public function testBuildOrderWithCompanyCustomer(){
             $request = WebPay::createOrder();
            //->setTestmode()();
        //foreach...
            $request = $request
            ->addOrderRow(
                Item::orderRow()
                    ->setArticleNumber(1)
                    ->setQuantity(2)
                    ->setAmountExVat(100.00)
                    ->setDescription("Specification")
                    ->setName('Prod')
                    ->setUnit("st")
                    ->setVatPercent(25)
                    ->setDiscountPercent(0))
                ->addCustomerDetails(Item::companyCustomer()
                    ->setNationalIdNumber(666666)
                    ->setEmail("test@svea.com")
                    ->setPhoneNumber(999999)
                    ->setIpAddress("123.123.123")
                    ->setStreetAddress("Gatan", 23)
                    ->setCoAddress("c/o Eriksson")
                    ->setZipCode(9999)
                    ->setLocality("Stan")
                       );                    
        //end foreach
            $request = $request
            ->setCountryCode("SE")
            ->setCustomerReference("33")
            ->setOrderDate("2012-12-12")
            ->setCurrency("SEK")
            ->useInvoicePayment()// returnerar InvoiceOrder object 
                ->prepareRequest();
        
            $this->assertEquals(666666, $request->request->CreateOrderInformation->CustomerIdentity->NationalIdNumber);
            $this->assertEquals("Company", $request->request->CreateOrderInformation->CustomerIdentity->CustomerType);
        }
        
        
      
        /** example how to integrate with array_map
        function testOrderRowsUsingMap(){
            $orderRows[] = array_map(magentoRowToOrderRow, $magentoRows);
            
            WebPay::createOrder()->addOrderRow(array_map(magentoRowToOrderRow, $magentoRows));
            
        }
        
        function magentoRowToOrderRow($magentoRow) {
             return WebPay::orderrow()
                        ->setArticleNumber($magentoRow->productId)
                        ->setQuantity(..)
                        ->setAmountExVat(...)
                        ->setDescription(...)
                        ->setName('Prod')
                        ->setUnit("st")
                        ->setVatPercent(25)
                        ->setDiscountPercent(0);
            
        }
 * 
 */

}


?>
