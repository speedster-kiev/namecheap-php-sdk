<?php

namespace Namecheap\Command\Domains;

use Namecheap\Command\ACommand;

/**
 * Implementation of Namecheaps domains.gettldlist command.
 *
 * Returns list of all available TLDs.
 * Important: please cache respobse to avoid repeated calls.
 *
 * @package Namecheap\Command\Domains
 */
class GetTldList extends ACommand
{

    /**
     * Holds received TLDs list.
     *
     * @var array
     */
    public $tlds = [];

    public function command()
    {
        return 'namecheap.domains.getTldList';
    }

    public function params()
    {
        return [];
    }

    /**
     * Process TLD list
     */
    protected function _postDispatch()
    {
        foreach ($this->_response->Tlds->Tld as $entry) {
            $tld = [];
            foreach ($entry->attributes() as $key => $value) {
                $tld[$key] = (string) $value;
            }
            $this->tlds[] = $tld;
        }
    }
}
