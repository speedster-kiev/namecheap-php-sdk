<?php

namespace Namecheap\Command\Users;

use Namecheap\Command\ACommand;

class GetPricing extends ACommand
{
    public $data = [];

    public function command()
    {
        return 'namecheap.users.getPricing';
    }

    public function params()
    {
        return [
            'ProductType'     => 'DOMAIN',
            'ProductCategory' => null,
        ];
    }

    /**
     * Process domains array
     */
    protected function _postDispatch()
    {
        foreach ($this->_response->UserGetPricingResult->attributes() as $key => $value) {
            $this->data[$key] = (string) $value;
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
        $this->getParam('DomainName');
    }
}
