<?php

use Viraj\Hawkeye\Hawkeye;

class HawkeyeTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    public function __construct()
    {
        $this->fileRepo = Mockery::mock('Viraj\Hawkeye\FileRepository');
        $this->hawkeye = new Hawkeye($this->fileRepo);
    }

    public function testInitializeHawkeye()
    {
        $hawkeye = new Hawkeye($this->fileRepo);
    }

    /**
     * @expectedException Viraj\Hawkeye\Exceptions\InvalidFileException
     */
    public function testRequestFails()
    {
        $this->hawkeye->request('');
    }

    public function testRequestPasses()
    {
        $uploaded_file = Mockery::partialMock('$_FILES[$filename]["tmp_name"]');

        $this->assertInstanceOf('Viraj\Hawkeye\UploadedFile', $this->hawkeye->request($uploaded_file));
    }

}