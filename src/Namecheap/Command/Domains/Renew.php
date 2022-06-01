<?php

namespace Namecheap\Command\Domains;

use Namecheap\Command\ACommand;

class Renew extends ACommand
{
    public $domain = [];

    public function command()
    {
        return 'namecheap.domains.renew';
    }

    public function params()
    {
        return [
            'DomainName' => null,
            'Years'      => 1,
        ];
    }

    /**
     * Process domains array
     */
    protected function _postDispatch()
    {
        foreach ($this->_response->DomainRenewResult->attributes() as $key => $value) {
            $this->domain[$key] = (string) $value;
        }
    }

    /**
     * Get/set method for domain list, limited to 70 characters
     *
     * @param string $value
     *
     * @return mixed
     */
    public function domainName($value = null)
    {
        if (null !== $value) {
            $this->setParam('DomainName', (string) substr($value, 0, 70));

            return $this;
        }

        return $this->getParam('DomainName');
    }
}
