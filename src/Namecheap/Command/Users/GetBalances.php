<?php

namespace Namecheap\Command\Users\GetBalances
{
	class Exception extends \Exception {}
}

namespace Namecheap\Command\Users
{
	class GetBalances extends \Namecheap\Command\ACommand
	{
		public $data = array();

		public function command()
		{
			return 'namecheap.users.getBalances';
		}

		public function params()
		{
			return array();
		}

		/**
		 * Process domains array
		 */
		protected function _postDispatch()
		{
			foreach ($this->_response->UserGetBalancesResult->attributes() as $key => $value)
			{
				$this->data[$key] = (string) $value;
			}
		}

		/**
		 * Get/set method for domain list, limited to 1024 characters
		 * @param string|array $value
		 * @return mixed
		 */
		public function domainName($value = null)
		{
			if (null !== $value)
			{
				$this->setParam('DomainName', (string) substr($value, 0, 70));
				return $this;
			}
			$this->getParam('DomainName');
		}
	}
}
