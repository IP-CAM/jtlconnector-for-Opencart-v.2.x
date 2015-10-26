<?php

namespace jtl\Connector\OpenCart\Tests\Utility;

use jtl\Connector\OpenCart\Utility\Utils;
use PHPUnit_Framework_TestCase;

class UtilsTest extends PHPUnit_Framework_TestCase
{

    public function testArrayRemove()
    {
        $colors = [
            'red' => 'tomato',
            'yellow' => 'banana',
            'green' => 'salad',
            'blue' => 'blueberry'
        ];
        $keyToRemove = 'green';
        $valueToRemove = $colors[$keyToRemove];
        $utils = Utils::getInstance();

        $value = $utils->array_remove($colors, $keyToRemove);
        $this->assertEquals($valueToRemove, $value);
        $this->assertFalse(array_key_exists($keyToRemove, $colors));

        $toRemove = 'black';
        $value = $utils->array_remove($colors, $toRemove);
        $this->assertNull($value);
        $this->assertFalse(array_key_exists($toRemove, $colors));
    }
}
