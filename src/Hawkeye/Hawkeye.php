<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\UploadedFile;
use Viraj\Hawkeye\Exceptions\InvalidFileException;

class Hawkeye
{
    private $_fileRepo;

    public function __construct(FileRepository $fileRepository)
    {
        $this->_fileRepo = $fileRepository;
    }

    public function request($filename)
    {
        if ($filename != '') {
            return new UploadedFile($_FILES[$filename]["tmp_name"]);
        }

        throw new InvalidFileException('Invalid');
    }

    public function isValidFileName($fileName)
    {
        return strlen($fileName) == 32 && ctype_xdigit($fileName);
    }
}
