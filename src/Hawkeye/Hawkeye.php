<?php namespace Viraj\Hawkeye;

use Symfony\Component\Finder\SplFileInfo;

class Hawkeye
{
    public function validateFile($fileInstance)
    {
        return $fileInstance instanceof SplFileInfo;
    }

    public function isValidFileName($fileName)
    {
        return strlen($fileName) == 32 && ctype_xdigit($fileName);
    }
}