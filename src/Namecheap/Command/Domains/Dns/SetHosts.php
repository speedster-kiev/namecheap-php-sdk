<?php

namespace Namecheap\Command\Domains\Dns\SetHosts
{
	class Exception extends \Exception {}
}

namespace Namecheap\Command\Domains\Dns
{
	class SetHosts extends \Namecheap\Command\ACommand
	{
		protected $_hosts = array();
		protected $_hostIdMap = array();

        /**
         * @var bool
         */
        public $success = false;

		public function command()
		{
			return 'namecheap.domains.dns.setHosts';
		}

		public function params()
		{
			return array(
				'TLD'	=> 'com',
				'SLD'	=> null,
			);
		}

		/**
		 * Overload parent method to handle custom parameters
		 */
		protected function _prepareParameters()
		{
			parent::_prepareParameters();

			$i = 1;
			foreach ($this->_hosts as $host)
			{
				$this->setParams(array(
					'HostName' . $i		=> $host->host,
					'RecordType' . $i	=> $host->type,
					'Address' . $i		=> $host->data,
					'MXPref' . $i		=> $host->mxPref,
					'TTL' . $i			=> $host->ttl,
				));
				$i += 1;
			}
		}

		/**
		 * Return the DnsRecord object for host
		 * @param int $index
		 * @return Namecheap\DnsRecord
		 */
		public function getHost($index)
		{
			return (isset($this->_hosts[$index])) ? $this->_hosts[$index] : false;
		}

		/**
		 * Return the DnsRecord object for host by looking up host id
		 * @param int $index
		 * @return Namecheap\DnsRecord
		 */
		public function getHostById($index)
		{
			// Get _hosts[index] from host id map array
			if (isset($this->_hostIdMap[$index]))
			{
				$index = $this->_hostIdMap[$index];
				return (isset($this->_hosts[$index])) ? $this->_hosts[$index] : false;
			}
			return false;
		}

		/**
		 * Set the DnsRecord object for host
		 * @param int $index
		 * @param Namecheap\DnsRecord
		 * @return mixed
		 */
		public function setHost($index, \Namecheap\DnsRecord $value)
		{
			$index = (int) $index;
			$this->_hosts[$index] = $value;
			return $this;
		}

		/**
		 * Add DnsRecord object for host
		 * @param Namecheap\DnsRecord $record
		 * @return Namecheap\DnsRecord
		 */
		public function addHost(\Namecheap\DnsRecord $record)
		{
			$this->_hosts[] = $record;
			return $this;
		}

		/**
		 * Remove a host
		 * @param int $index, array index to remove
		 * @return Namecheap\DnsRecord
		 */
		public function removeHost($index)
		{
			$index = (int) $index;
			if (isset($this->_hosts[$index]))
			{
				$hostId = $this->_hosts[$index]->hostId;
				unset($this->_hostIdMap[$hostId]);
				unset($this->_hosts[$index]);
			}

			return $this;
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
		 * Return the array of hosts
		 * @param array $value, array of hosts to set
		 * @return array
		 */
		public function hosts($value = null)
		{
			if (null !== $value && is_array($value))
			{
				$this->_hosts = $value;
				return $this;
			}
			return (array) $this->_hosts;
		}

		/**
		 * Return the DnsRecord object for host
		 * @param int $index
		 * @param Namecheap\DnsRecord $value
		 * @return Namecheap\DnsRecord
		 */
		public function host($index, \Namecheap\DnsRecord $value = null)
		{
			$index = (int) $index;
			if (null !== $value)
			{
				return $this->setHost($index, $value);
			}
			return $this->getHost($index);
		}

        public function _postDispatch() {
            $attributes = $this->_response->DomainDNSSetHostsResult->attributes();
            if ('true' === (string)$attributes['IsSuccess']) {
                $this->success = true;
            }
        }
	}
}
