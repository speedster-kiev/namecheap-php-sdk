<?php

namespace Namecheap\Command;

use Namecheap\Config;

interface ICommand
{
    /**
     * @param Config $config
     */
    public function config(Config $config);

    /**
     * Should return the command string
     *
     * @return string
     */
    public function command();

    /**
     * Should return an array of parameters that the extending command is adding along with default values
     *
     * @return array
     */
    public function params();
}
