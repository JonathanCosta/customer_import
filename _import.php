<?php
define('MAGENTO', realpath(dirname(__FILE__)));
require_once MAGENTO . '/app/Mage.php';

umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$count = 0;
$file = fopen('./var/import/customer_.csv', 'r');
$indices;

while (($line = fgetcsv($file)) !== FALSE)
{
	$count++;
	$num = count($line);
	if ($count <= 1)
	{
		for ($c = 0; $c < $num; $c++)
		{
			$indices[$line[$c]] = '';
		}

		continue;
	}

	$data = $indices;

	if (!empty($line[0]) && !empty($line[1]))
	{
		$count2 = 0;
		foreach($data as $key => $val)
		{
			$data[$key] = $line[$count2];
			$count2++;
		}

		forceCreateCustomer($data);
		sleep(0.5);
		unset($data);
	}
}

echo "\n Fim da importaÃ§Ã£o confira o arquivo accountserror.log para detalhes sobre erro\n";

function forceCreateCustomer($data)
{
	echo "\n Starting {$data['email']}.";
	$customer = new Mage_Customer_Model_Customer();
	try
	{
		$customer->setData($data)->save();
		echo '.';
		$address = Mage::getModel("customer/address");
		$address->setCustomerId($customer->getId())
				->setFirstname($data['_address_firstname'])
				->setMiddleName($customer->getMiddlename())
				->setLastname($data['_address_lastname'])
				->setCountryId($data['_address_country_id'])
				->setRegionId($data['_address_region'])
				->setPostcode($data['_address_postcode'])
				->setCity($data['_address_city'])
				->setTelephone($data['_address_telephone'])
				->setFax($data['_address_telephone'])
				->setCompany($data['_address_company'])
				->setStreet($data['_address_street'])
				->setIsDefaultBilling('1')
				->setIsDefaultShipping('1')
				->setSaveInAddressBook('1');
				
		echo '.';
		$address->save();
		echo '.';
	}

	catch(Exception $e)
	{
		echo '.';
		echo '.';
		$content = 'erro no email ' . $data['email'] . '->' . $e->getMessage() . "\n\n\n";
		echo '.';
		file_put_contents('./accountserror.log', $content);
		echo '.';
	}

	echo " \n Suceeded \n\n";
}

?>
