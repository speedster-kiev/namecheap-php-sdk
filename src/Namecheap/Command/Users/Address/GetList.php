<?php

namespace Namecheap\Command\Users\Address;

use Namecheap\Command\ACommand;

/**
 * Gets a list of addressIDs and address names associated with the user account.
 *
 * @package Namecheap\Command\Users\Address
 */
class GetList extends ACommand
{

    /**
     * @var array
     */
    public $addresses = [];

    /**
     * @return string Namecheap command name
     */
    public function command()
    {
        return 'namecheap.users.address.getList';
    }

    public function params()
    {
        return [];
    }

    /**
     * Process addresses list.
     */
    protected function _postDispatch()
    {
        foreach ($this->_response->AddressGetListResult->List as $entry) {
            $address = [];
            foreach ($entry->attributes() as $key => $value) {
                $address[$key] = (string) $value;
            }
            $this->addresses[$address['AddressName']] = $address;
        }
    }
}
