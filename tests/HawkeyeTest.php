<?php

use Viraj\Hawkeye\Hawkeye;

class HawkeyeTest extends PHPUnit_Framework_TestCase
{

    protected $_hawkeye;

    public function tearDown()
    {
        Mockery::close();
    }

    public function __construct()
    {

    }

    public function testInitializeHawkeye()
    {
        $this->_hawkeye = new Hawkeye();
    }


}