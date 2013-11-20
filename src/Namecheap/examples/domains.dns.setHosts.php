<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', true);
ini_set('display_errors', true);

include_once '../Api.php';

try
{
	$config = new \Namecheap\Config();
	$config->apiUser('api-username')
		->apiKey('api-key')
		->clientIp('your-ip')
		->sandbox(true);

	/*
	$command = Namecheap\Api::factory($config, 'domains.dns.getHosts');
	$command->domainName('example1.com')->dispatch();

	if ($record = $command->getHostById('123'))
	{
		$record->setData('example1.com');
	}

	if ($record = $command->getHostById('123'))
	{
		$record->setData('192.168.1.1');
	}
	*/
#	$record = new Namecheap\DnsRecord();
#	$command->addHost($record);
#	$command->removeHost(1);

	$command = Namecheap\Api::factory($config, 'domains.dns.setHosts');
	$command->domainName('example1.com');

	$record = new Namecheap\DnsRecord();
	$record->setType('A')->setHost('@')->setData('192.168.1.1');
	$command->addHost($record);

	$record = new Namecheap\DnsRecord();
	$record->setType('cname')->setHost('www')->setData('example1.com');
	$command->addHost($record);

	$command->dispatch();
} catch (\Exception $e) {
	die($e->getMessage());
}

d($command);

function d($value = array())
{
	echo '<pre>' . "\n";
	print_r($value);
	die('</pre>' . "\n");
}
