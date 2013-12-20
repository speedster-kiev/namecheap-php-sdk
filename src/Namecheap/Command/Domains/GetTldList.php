<?php
/**
 * @author Maksym Karazieiev <mkarazeev@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 */

namespace Namecheap\Command\Domains\GetTldList
{
    class Exception extends \Exception {}
}

namespace Namecheap\Command\Domains
{
    /**
     * Implementation of Namecheaps domains.gettldlist command.
     *
     * Returns list of all available TLDs.
     * Important: please cache respobse to avoid repeated calls.
     *
     * @package Namecheap\Command\Domains
     */
    class GetTldList extends \Namecheap\Command\ACommand{

        /**
         * Holds received TLDs list.
         * @var array
         */
        public $tlds = array();

        public function command()
        {
            return 'namecheap.domains.getTldList';
        }

        public function params()
        {
            return array();
        }

        /**
         * Process TLD list
         */
        protected function _postDispatch()
        {
            foreach ($this->_response->Tlds->Tld as $entry)
            {
                $tld = array();
                foreach ($entry->attributes() as $key => $value)
                {
                    $tld[$key] = (string) $value;
                }
                $this->tlds[] = $tld;
            }
        }
    }

} 