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

	$command = Namecheap\Api::factory($config, 'domains.ns.update');
	$command->domainName('example1.com')
		->nameserver('ns1.example1.com')
		->oldIp('192.168.1.1')
		->ip('192.168.1.2')
		->dispatch();
} catch (\Exception $e) {
	die($e->getMessage());
}

if ($command->status() == 'error') { die($command->errorMessage); }
d($command);

function d($value = [])
{
	echo '<pre>' . "\n";
	print_r($value);
	die('</pre>' . "\n");
}
