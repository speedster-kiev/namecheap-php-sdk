<?php

namespace Namecheap\Command\Domains\Check
{
	class Exception extends \Exception {}
}

namespace Namecheap\Command\Domains
{
	class Check extends \Namecheap\Command\ACommand
	{
		public $domains = array();

		public function command()
		{
			return 'namecheap.domains.check';
		}

		public function params()
		{
			return array(
				'DomainList' => array(),
			);
		}

		/**
		 * Process domains array
		 */
		protected function _postDispatch()
		{
			$this->domains = array();
			foreach ($this->_response->DomainCheckResult as $entry)
			{
				$this->domains[(string) $entry['Domain']] = ('true' == strtolower((string) $entry['Available'])) ? true : false;
			}
		}

		/**
		 * Get/set method for domain list, limited to 1024 characters
		 * @param string|array $value
		 * @return mixed
		 */
		public function domainList($value = null)
		{
			if (null !== $value)
			{
				// If array of domains is passed, convert to comma separated
				if (is_array($value)) { $value = implode(',', $value); }

				$this->setParam('DomainList', (string) substr($value, 0, 1024));
				return $this;
			}
			return $this->getParam('DomainList');
		}

		/**
		 * Check if the domain is available from the returned api result. Will return false if the domain is not set
		 * @param string $domain
		 * @return bool
		 */
		public function isAvailable($domain)
		{
			// Throw exception if the requested domain was not returned by the api
			if (!isset($this->domains[$domain]))
			{
				throw new Check\Exception($domain . ' was not in result set');
			}

			return ($this->domains[$domain] === true) ? true : false;
		}
	}
}
