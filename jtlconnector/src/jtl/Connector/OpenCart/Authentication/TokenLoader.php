<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Authentication
 */

namespace jtl\Connector\OpenCart\Authentication;

use jtl\Connector\Authentication\ITokenLoader;
use jtl\Connector\OpenCart\Utility\OpenCart;

class TokenLoader implements ITokenLoader
{

    /**
     * Loads the on installation generated connector token.
     *
     * @return string
     */
    public function load()
    {
        return OpenCart::getInstance()->loadToken();
    }
}
