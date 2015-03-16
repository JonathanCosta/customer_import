<?php

define('MAGENTO', realpath(dirname(__FILE__)));
require_once MAGENTO . '/app/Mage.php';
 
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$count = 0;
 
$file = fopen('./var/import/customer_.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) { $count++;
//$line is an array ovaf the csv elements
/*print('<pre>');
 var_dump($line);
 print('</pre>');
*/
//define o começo dos dados
if($count<=1){continue;}

if (!empty($line[0]) && !empty($line[1])) {
 
$data['email'] = $line[0];
$data['_website'] = $line[1];
$data['confirmation'] = $line[2];
$data['created_at'] = $line[3];
$data['firstname'] = $line[4];
$data['gender'] = $line[5];
$data['group_id'] = $line[6];
$data['ie'] = $line[7];
$data['lastname'] = $line[8]; 
$data['middlename'] = $line[9];
$data['password_hash'] = $line[10];
$data['prefix'] = $line[11];
$data['rp_token'] = $line[12];
$data['rp_token_created_at'] = $line[13];
$data['store_id'] = $line[14];
$data['suffix'] = $line[15];
$data['taxvat'] = $line[16];
$data['tipopessoa'] = $line[17]; 
$data['website_id'] = $line[18];
$data['password'] = $line[19];
$data['_address_city'] = $line[20];
$data['_address_company'] = $line[21];
$data['_address_country_id'] = $line[22];
$data['_address_fax'] = $line[23];
$data['_address_firstname'] = $line[24];
$data['_address_lastname'] = $line[25];
$data['_address_middlename'] = $line[26]; 
$data['_address_postcode'] = $line[27];
$data['_address_prefix'] = $line[28];
$data['_address_region'] = $line[29];
$data['_address_street'] = $line[30];
$data['_address_suffix'] = $line[31];
$data['_address_telephone'] = $line[32];
$data['_address_vat_id'] = $line[33];
$data['_address_default_billing_'] = $line[34];
$data['_address_default_shipping_'] = $line[35];

$storeId = 0;
  createCategory($data) ;
//sleep(0.7);

/*print('<pre>');
 var_dump($data);
 print('</pre>');
*/
unset($data);
}
 
}
 
function createCategory($data) {
 
echo "Starting {$data['email']}...";


			//Zend_Debug::dump($data);
		
		//exit;
	
            //Instancia o Customer do Magento
            $customer = new Mage_Customer_Model_Customer();
            
            // Instancia o endereço do customer

         //   $endereco = new Mage_Customer_Model_Address();
            
            
			
           
            //Salva o cliente no Magento
           
			
			
			
			try{
    $customer->setData($data)->save();
            //Salva endereco do cliente
  $address = Mage::getModel("customer/address");
$address->setCustomerId($customer->getId())
        ->setFirstname($data['_address_firstname'])
        ->setMiddleName($customer->getMiddlename())
        ->setLastname($data['_address_lastname'])
        ->setCountryId( $data['_address_country_id'])
		->setRegionId($data['_address_region']) //state/province, only needed if the country is USA
        ->setPostcode($data['_address_postcode'])
        ->setCity($data['_address_city'])
        ->setTelephone($data['_address_telephone'])
        ->setFax($data['_address_telephone'])
        ->setCompany($data['_address_company'])
        ->setStreet($data['_address_street'])
        ->setIsDefaultBilling('1')
        ->setIsDefaultShipping('1')
        ->setSaveInAddressBook('1');
 
try{
    $address->save();
}
catch (Exception $e) {
    Zend_Debug::dump($e->getMessage());
}
}
catch (Exception $e) {
	
	$content = 'erro no email'.$data['email'].'->'.$e->getMessage()."\n\n\n";
// armazenar todas as senhas em um arquivo:
file_put_contents('./accountserror.log', $content);
  
}


                    echo " <br />Suceeded <hr />\n"; 

           
        
 
 
}
