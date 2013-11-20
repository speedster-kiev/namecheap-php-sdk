<?php

namespace Namecheap\Command\Domains\GetContacts
{
	class Exception extends \Exception {}
}

namespace Namecheap\Command\Domains
{
	class GetContacts extends \Namecheap\Command\ACommand
	{
		public $registrant = array();
		public $tech = array();
		public $admin = array();
		public $auxBilling = array();

		public function command()
		{
			return 'namecheap.domains.getContacts';
		}

		public function params()
		{
			return array(
				'DomainName'	=> null,
			);
		}

		/**
		 * Process domains array
		 */
		protected function _postDispatch()
		{
			$this->registrant = array();
			foreach ($this->_response->DomainContactsResult->Registrant as $entry)
			{
				foreach ($entry as $key => $value)
				{
					$this->registrant[$key] = (string) $value;
				}
			}

			$this->tech = array();
			foreach ($this->_response->DomainContactsResult->Tech as $entry)
			{
				foreach ($entry as $key => $value)
				{
					$this->tech[$key] = (string) $value;
				}
			}

			$this->admin = array();
			foreach ($this->_response->DomainContactsResult->Admin as $entry)
			{
				foreach ($entry as $key => $value)
				{
					$this->admin[$key] = (string) $value;
				}
			}

			$this->auxBilling = array();
			foreach ($this->_response->DomainContactsResult->AuxBilling as $entry)
			{
				foreach ($entry as $key => $value)
				{
					$this->auxBilling[$key] = (string) $value;
				}
			}
		}

		/**
		 * Get/set method for domain name
		 * @param string $value
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
