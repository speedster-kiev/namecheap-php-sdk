<?php

namespace Namecheap\Command\Domains;

use Namecheap\Command\ACommand;

class GetContacts extends ACommand
{
    public $registrant = [];
    public $tech = [];
    public $admin = [];
    public $auxBilling = [];

    public function command()
    {
        return 'namecheap.domains.getContacts';
    }

    public function params()
    {
        return [
            'DomainName' => null,
        ];
    }

    /**
     * Process domains array
     */
    protected function _postDispatch()
    {
        $this->registrant = [];
        foreach ($this->_response->DomainContactsResult->Registrant as $entry) {
            foreach ($entry as $key => $value) {
                $this->registrant[$key] = (string) $value;
            }
        }

        $this->tech = [];
        foreach ($this->_response->DomainContactsResult->Tech as $entry) {
            foreach ($entry as $key => $value) {
                $this->tech[$key] = (string) $value;
            }
        }

        $this->admin = [];
        foreach ($this->_response->DomainContactsResult->Admin as $entry) {
            foreach ($entry as $key => $value) {
                $this->admin[$key] = (string) $value;
            }
        }

        $this->auxBilling = [];
        foreach ($this->_response->DomainContactsResult->AuxBilling as $entry) {
            foreach ($entry as $key => $value) {
                $this->auxBilling[$key] = (string) $value;
            }
        }
    }

    /**
     * Get/set method for domain name
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
        $this->getParam('DomainName');
    }

}
