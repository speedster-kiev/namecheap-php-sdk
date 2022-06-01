<?php

namespace Namecheap\Command\Domains\Dns;

use Namecheap\Command\ACommand;

class SetDefault extends ACommand
{
    public $domains = [];

    public function command()
    {
        return 'namecheap.domains.dns.setDefault';
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
        $this->domains = [];

        foreach ($this->_response->DomainDNSSetDefaultResult as $entry) {
            $domain = [];
            foreach ($entry->attributes() as $key => $value) {
                $domain[$key] = (string) $value;
            }
            $this->domains[$domain['Domain']] = $domain;
        }
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
}
