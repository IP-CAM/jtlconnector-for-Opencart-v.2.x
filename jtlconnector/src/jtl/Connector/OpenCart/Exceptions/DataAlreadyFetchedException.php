<?php

namespace jtl\Connector\OpenCart\Exceptions;

use Exception;

class DataAlreadyFetchedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("This method do not has to be called as the required data is already there. Please check
        which database calls are already made");
    }

}