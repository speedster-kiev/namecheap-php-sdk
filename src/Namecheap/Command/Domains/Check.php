<?php

namespace Namecheap\Command\Domains;

use Namecheap\Command\ACommand;
use Namecheap\Exceptions\NamecheapException;

class Check extends ACommand
{
    public $domains = [];

    public function command()
    {
        return 'namecheap.domains.check';
    }

    public function params()
    {
        return [
            'DomainList' => [],
        ];
    }

    /**
     * Process domains array
     */
    protected function _postDispatch()
    {
        $this->domains = [];
        foreach ($this->_response->DomainCheckResult as $entry) {
            $this->domains[(string) $entry['Domain']] = 'true' == strtolower((string) $entry['Available']);
        }
    }

    /**
     * Get/set method for domain list, limited to 120 domains
     *
     * @param string|array $value
     *
     * @return mixed
     */
    public function domainList($value = null)
    {
        if (null !== $value) {
            if (is_string($value)) {
                $value = explode(',', $value);
            }

            $value = array_slice($value, 0, 120); // See https://community.namecheap.com/forums/viewtopic.php?f=17&t=7568
            $value = implode(',', $value);

            $this->setParam('DomainList', (string) $value);

            return $this;
        }

        return $this->getParam('DomainList');
    }

    /**
     * Check if the domain is available from the returned api result. Will return false if the domain is not set
     *
     * @param string $domain
     *
     * @return bool
     */
    public function isAvailable($domain)
    {
        // Throw exception if the requested domain was not returned by the api
        if (!isset($this->domains[$domain])) {
            throw new NamecheapException($domain . ' was not in result set');
        }

        return $this->domains[$domain] === true;
    }
}
