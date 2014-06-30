<?php

namespace Namecheap\Command\Users\GetPricing
{
	class Exception extends \Exception {}
}

namespace Namecheap\Command\Users
{
	class GetPricing extends \Namecheap\Command\ACommand
	{
		public $data = array();

		public function command()
		{
			return 'namecheap.users.getPricing';
		}

		public function params()
		{
			return array(
                'ProductType'	=> 'DOMAIN',
                'ProductCategory'	=> null,
						);
		}

		/**
		 * Process domains array
		 */
		protected function _postDispatch()
		{
			foreach ($this->_response->UserGetPricingResult->attributes() as $key => $value)
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
