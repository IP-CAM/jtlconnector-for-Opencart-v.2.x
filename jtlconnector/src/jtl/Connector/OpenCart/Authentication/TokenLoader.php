<?php

namespace jtl\Connector\OpenCart\Authentication;

use jtl\Connector\Authentication\ITokenLoader;
use jtl\Connector\OpenCart\Utility\OpenCart;

class TokenLoader implements ITokenLoader
{

    /**
     * Loads the connector token
     *
     * @return string
     */
    public function load()
    {
        return OpenCart::getInstance()->getConfig('connector-password');
    }
}
