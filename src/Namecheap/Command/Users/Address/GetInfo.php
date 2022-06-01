<?php

namespace Namecheap\Command\Users\Address;

use Namecheap\Command\ACommand;

/**
 * Gets information for the requested addressID.
 *
 * @package Namecheap\Command\Users\Address
 */
class GetInfo extends ACommand
{

    /**
     * @var array
     */
    public $address = [];

    /**
     * Should return the command string
     *
     * @return string
     */
    public function command()
    {
        return 'namecheap.users.address.getInfo';
    }

    /**
     * Should return an array of parameters that the extending command is adding along with default values
     *
     * @return array
     */
    public function params()
    {
        return [
            'AddressId' => null,
        ];
    }

    protected function _postDispatch()
    {
        foreach ($this->_response->GetAddressInfoResult as $entry) {
            foreach ($entry as $key => $value) {
                $this->address[$key] = (string) $value;
            }
        }
    }

    /**
     * Get/set method for AddressId
     *
     * @param int $id
     */
    public function addressId($id = null)
    {
        if ($id === null) {
            return $this->getParam('AddressId');
        }
        $this->setParam('AddressId', (int) substr($id, 0, 20));

        return $this;
    }
}
