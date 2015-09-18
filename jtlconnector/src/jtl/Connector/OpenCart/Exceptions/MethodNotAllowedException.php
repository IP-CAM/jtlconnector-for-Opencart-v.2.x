<?php

namespace jtl\Connector\OpenCart\Exceptions;

class MethodNotAllowedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("This method isn't allowed to be called. The Wawi handles the data.");
    }

}