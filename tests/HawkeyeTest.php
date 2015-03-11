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
        $this->_hawkeye = new Hawkeye();
    }

    public function testValidateFileSuccess()
    {
        $mock = Mockery::mock('Symfony\Component\Finder\SplFileInfo');

        $this->assertTrue($this->_hawkeye->validateFile($mock));
    }

    public function testValidateFileFails()
    {
        $mock = Mockery::mock('Hawkeye');

        $this->assertFalse($this->_hawkeye->validateFile($mock));
    }

    public function testGetFilePathFromName()
    {

    }

    public function testIsValidFileNameFails()
    {
        $fileName = "";

        $this->assertFalse($this->_hawkeye->isValidFileName($fileName));
    }

}