<?php
/**
 * @author Maksym Karazieiev <mkarazeev@gmail.com>
 */

namespace Namecheap\Command\Users\Address\GetList
{
    class Exception extends \Exception {}
}

namespace Namecheap\Command\Users\Address {

    /**
     * Gets a list of addressIDs and addressnames associated with the user account.
     *
     * @package Namecheap\Command\Users\Address
     */
    class GetList extends \Namecheap\Command\ACommand
    {

        /**
         * @var array
         */
        public $addresses = array();

        /**
         * @return string Namecheap command name
         */
        public function command()
        {
            return 'namecheap.users.address.getList';
        }

        public function params()
        {
            return array();
        }

        /**
         * Process addresses list.
         */
        protected function _postDispatch() {
            foreach ($this->_response->AddressGetListResult->List as $entry)
            {
                $address = array();
                foreach ($entry->attributes() as $key => $value)
                {
                    $address[$key] = (string) $value;
                }
                $this->addresses[$address['AddressName']] = $address;
            }
        }
    }

}