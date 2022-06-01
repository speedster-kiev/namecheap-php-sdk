<?php

namespace Namecheap\Command\Domains\Dns;

use Namecheap\Command\ACommand;
use Namecheap\DnsRecord;

class GetHosts extends ACommand
{
    public $data = [];
    protected $_hosts = [];
    protected $_hostIdMap = [];

    public function command()
    {
        return 'namecheap.domains.dns.getHosts';
    }

    public function params()
    {
        return [
            'TLD' => 'com',
            'SLD' => null,
        ];
    }

    /**
     * Process domains array
     */
    protected function _postDispatch()
    {
        $result = $this->_response->DomainDNSGetHostsResult;

        $this->data = [];
        $this->data['emailType'] = (string) $result->attributes()->EmailType;
        $this->data['namecheapDns'] = (string) $result->attributes()->IsUsingOurDNS === 'true';

        // Process host records
        $this->_hosts = [];
        $index = 0;
        foreach ($this->_response->DomainDNSGetHostsResult->host as $entry) {
            $domain = [];
            foreach ($entry->attributes() as $key => $value) {
                $domain[$key] = (string) $value;
            }
            $this->_hosts[] = new DnsRecord($domain);
            $this->_hostIdMap[$domain['HostId']] = $index;
            ++$index;
        }
    }

    /**
     * Return the DnsRecord object for host
     *
     * @param int $index
     *
     * @return DnsRecord
     */
    public function getHost($index)
    {
        return $this->_hosts[$index] ?? false;
    }

    /**
     * Return the DnsRecord object for host by looking up host id
     *
     * @param int $index
     *
     * @return DnsRecord
     */
    public function getHostById($index)
    {
        // Get _hosts[index] from host id map array
        if (isset($this->_hostIdMap[$index])) {
            $index = $this->_hostIdMap[$index];

            return $this->_hosts[$index] ?? false;
        }

        return false;
    }

    /**
     * Set the DnsRecord object for host
     *
     * @param int $index
     * @param DnsRecord
     *
     * @return mixed
     */
    public function setHost($index, DnsRecord $value)
    {
        $index = (int) $index;
        $this->_hosts[$index] = $value;

        return $this;
    }

    /**
     * Add DnsRecord object for host
     *
     * @param DnsRecord $record
     *
     * @return DnsRecord
     */
    public function addHost(DnsRecord $record)
    {
        $this->_hosts[] = $record;

        return $this;
    }

    /**
     * Remove a host
     *
     * @param int $index array index to remove
     *
     * @return DnsRecord
     */
    public function removeHost($index)
    {
        $index = (int) $index;
        if (isset($this->_hosts[$index])) {
            $hostId = $this->_hosts[$index]->hostId;
            unset($this->_hostIdMap[$hostId], $this->_hosts[$index]);
        }

        return $this;
    }

    /**
     * Get/set method for domain name, which is comprised of sld + tld
     *
     * @param string $value
     *
     * @return mixed
     */
    public function domainName($value = null)
    {
        if (null !== $value) {
            list($sld, $tld) = explode('.', $value);
            $this->setParam('SLD', (string) substr($sld, 0, 70));
            $this->setParam('TLD', (string) substr($tld, 0, 10));

            return $this;
        }

        return $this->getParam('SLD') . '.' . $this->getParam('TLD');
    }

    /**
     * Return the array of hosts
     *
     * @param array $value , array of hosts to set
     *
     * @return array
     */
    public function hosts($value = null)
    {
        if (is_array($value)) {
            $this->_hosts = $value;

            return $this;
        }

        return (array) $this->_hosts;
    }

    /**
     * Return the DnsRecord object for host
     *
     * @param int $index
     * @param DnsRecord $value
     *
     * @return DnsRecord
     */
    public function host($index, DnsRecord $value = null)
    {
        $index = (int) $index;
        if (null !== $value) {
            return $this->setHost($index, $value);
        }

        return $this->getHost($index);
    }
}
