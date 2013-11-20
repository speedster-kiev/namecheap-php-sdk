<?php

namespace Namecheap\Command\Domains\Dns\SetCustom
{
	class Exception extends \Exception {}
}

namespace Namecheap\Command\Domains\Dns
{
	class SetCustom extends \Namecheap\Command\ACommand
	{
		public $domains = array();

		public function command()
		{
			return 'namecheap.domains.dns.setCustom';
		}

		public function params()
		{
			return array(
				'TLD'			=> 'com',
				'SLD'			=> null,
				'Namservers'	=> null,
			);
		}

		/**
		 * Process domains array
		 */
		protected function _postDispatch()
		{
			$this->domains = array();

			foreach ($this->_response->DomainDNSSetCustomResult as $entry)
			{
				$domain = array();
				foreach ($entry->attributes() as $key => $value)
				{
					$domain[$key] = (string) $value;
				}
				$this->domains[$domain['Domain']] = $domain;
			}
		}

		/**
		 * Get/set method for domain name, which is comprised of sld + tld
		 * @param string $value
		 * @return mixed
		 */
		public function domainName($value = null)
		{
			if (null !== $value)
			{
				list($sld, $tld) = explode('.', $value);
				$this->setParam('SLD', (string) substr($sld, 0, 70));
				$this->setParam('TLD', (string) substr($tld, 0, 10));
				return $this;
			}
			return $this->getParam('SLD') . '.' . $this->getParam('TLD');
		}

		/**
		 * Get/set method for nameservers
		 * @param string|array $value
		 * @return mixed
		 */
		public function nameservers($value = null)
		{
			if (null !== $value)
			{
				// If array of domains is passed, convert to comma separated
				if (is_array($value)) { $value = implode(',', $value); }

				$this->setParam('Nameservers', (string) substr($value, 0, 1024));
				return $this;
			}
			return $this->getParam('Nameservers');
		}
	}
}
