<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\UploadedFile;
use Viraj\Hawkeye\Exceptions\InvalidFileException;

class Hawkeye
{

    public function request($filename)
    {
        if ($filename != '') {
            return new UploadedFile($filename, new FileRepository());
        }

        throw new InvalidFileException("File is corrupted or Invalid");
    }

    public function isValidMd5Name($fileName)
    {
        return strlen($fileName) == 32 && ctype_xdigit($fileName);
    }
}
