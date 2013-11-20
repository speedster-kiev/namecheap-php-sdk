<?php

namespace Namecheap\Command\Domains\Ns\Delete
{
	class Exception extends \Exception {}
}

namespace Namecheap\Command\Domains\Ns
{
	class Delete extends \Namecheap\Command\ACommand
	{
		public $domains = array();

		public function command()
		{
			return 'namecheap.domains.ns.delete';
		}

		public function params()
		{
			return array(
				'TLD'			=> 'com',
				'SLD'			=> null,
				'Nameserver'	=> null,
			);
		}

		/**
		 * Process domains array
		 */
		protected function _postDispatch()
		{
			$this->domains = array();

			foreach ($this->_response->DomainNSDeleteResult as $entry)
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
		 * Get/set method for nameserver
		 * @param string $value
		 * @return mixed
		 */
		public function nameserver($value = null)
		{
			if (null !== $value)
			{
				$this->setParam('Nameserver', (string) substr($value, 0, 150));
				return $this;
			}
			return $this->getParam('Nameserver');
		}
	}
}
