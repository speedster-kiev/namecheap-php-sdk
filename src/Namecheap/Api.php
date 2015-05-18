<?php

namespace Namecheap\Api
{
	class Exception extends \Exception {}
}

namespace Namecheap
{
    include_once dirname(__FILE__) . '/../../vendor/autoload.php';

	class Api
	{
		/**
		 * Array of possible commands and associated class names
		 * @var array
		 */
		protected static $_commands = array(
			'domains.getList'			=> 'Namecheap\Command\Domains\GetList',
			'domains.getContacts'		=> 'Namecheap\Command\Domains\GetContacts',
			'domains.create'			=> 'Namecheap\Command\Domains\Create',
			'domains.check'				=> 'Namecheap\Command\Domains\Check',
			'domains.renew'				=> 'Namecheap\Command\Domains\Renew',
			'domains.getTldList'		=> 'Namecheap\Command\Domains\GetTldList',
			'domains.dns.setDefault'	=> 'Namecheap\Command\Domains\Dns\SetDefault',
			'domains.dns.setCustom'		=> 'Namecheap\Command\Domains\Dns\SetCustom',
			'domains.dns.getList'		=> 'Namecheap\Command\Domains\Dns\GetList',
			'domains.dns.getHosts'		=> 'Namecheap\Command\Domains\Dns\GetHosts',
			'domains.dns.setHosts'		=> 'Namecheap\Command\Domains\Dns\SetHosts',
			'domains.ns.create'			=> 'Namecheap\Command\Domains\Ns\Create',
			'domains.ns.delete'			=> 'Namecheap\Command\Domains\Ns\Delete',
			'domains.ns.getInfo'		=> 'Namecheap\Command\Domains\Ns\GetInfo',
			'domains.ns.update'			=> 'Namecheap\Command\Domains\Ns\Update',
			'users.getBalances'			=> 'Namecheap\Command\Users\GetBalances',
			'users.getPricing'			=> 'Namecheap\Command\Users\GetPricing',
			'users.address.getList'		=> 'Namecheap\Command\Users\Address\GetList',
			'users.address.getInfo'		=> 'Namecheap\Command\Users\Address\GetInfo',
		);

		/**
		 * @return Namecheap\Command\ACommand
		 * @throws Api\Exception
		 */
		public static function factory($config, $command)
		{
			if (!array_key_exists($command, static::$_commands))
			{
				throw new Api\Exception($command . ' is not a valid API');
			}

			$instance = new static::$_commands[$command]();
			return $instance->config($config);
		}
	}
}
