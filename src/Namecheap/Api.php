<?php

namespace Namecheap;

use Namecheap\Command;
use Namecheap\Exceptions\NamecheapException;

class Api
{
    /**
     * Array of possible commands and associated class names
     *
     * @var array
     */
    protected static $_commands = [
        'domains.getList'        => Command\Domains\GetList::class,
        'domains.getContacts'    => Command\Domains\GetContacts::class,
        'domains.getInfo'        => Command\Domains\GetInfo::class,
        'domains.create'         => Command\Domains\Create::class,
        'domains.check'          => Command\Domains\Check::class,
        'domains.renew'          => Command\Domains\Renew::class,
        'domains.getTldList'     => Command\Domains\GetTldList::class,
        'domains.dns.setDefault' => Command\Domains\Dns\SetDefault::class,
        'domains.dns.setCustom'  => Command\Domains\Dns\SetCustom::class,
        'domains.dns.getList'    => Command\Domains\Dns\GetList::class,
        'domains.dns.getHosts'   => Command\Domains\Dns\GetHosts::class,
        'domains.dns.setHosts'   => Command\Domains\Dns\SetHosts::class,
        'domains.ns.create'      => Command\Domains\Ns\Create::class,
        'domains.ns.delete'      => Command\Domains\Ns\Delete::class,
        'domains.ns.getInfo'     => Command\Domains\Ns\GetInfo::class,
        'domains.ns.update'      => Command\Domains\Ns\Update::class,
        'users.getBalances'      => Command\Users\GetBalances::class,
        'users.getPricing'       => Command\Users\GetPricing::class,
        'users.address.getList'  => Command\Users\Address\GetList::class,
        'users.address.getInfo'  => Command\Users\Address\GetInfo::class,
    ];

    /**
     * @param $config
     * @param $command
     *
     * @throws NamecheapException
     * @return ACommand
     */
    public static function factory($config, $command)
    {
        if (!array_key_exists($command, static::$_commands)) {
            throw new NamecheapException($command . ' is not a valid API');
        }

        return (new static::$_commands[$command]())->config($config);
    }
}
