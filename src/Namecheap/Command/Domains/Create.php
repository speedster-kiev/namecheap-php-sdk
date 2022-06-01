<?php

namespace Namecheap\Command\Domains;

use Namecheap\Command\ACommand;

class Create extends ACommand
{
    public $domain = [];

    public function command()
    {
        return 'namecheap.domains.create';
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
        foreach ($this->_response->DomainCreateResult->attributes() as $key => $value) {
            $this->domain[$key] = (string) $value;
        }
    }

    /**
     * Get/set method for domain list, limited to 1024 characters
     *
     * @param string|array $value
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
