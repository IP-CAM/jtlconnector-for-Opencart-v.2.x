<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Authentication;

use jtl\Connector\Authentication\ITokenLoader;
use jtl\Connector\OpenCart\Utility\OpenCart;

class TokenLoader implements ITokenLoader
{

    /**
     * Loads the on installation generated connector token.
     *
     * @return string The token
     */
    public function load()
    {
        return OpenCart::getInstance()->loadToken();
    }
}
