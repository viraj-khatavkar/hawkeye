<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\Exceptions\DiretoryNotCreatedException;
use Viraj\Hawkeye\Exceptions\InvalidMd5HashException;

trait HawkeyeTrait
{
    public function generateDirectoryPathFromName($hashedFileName)
    {
        if ($this->isValidMd5Name($hashedFileName)) {
            return implode('/', str_split($hashedFileName, 3));
        }
        throw new InvalidMd5HashException("The hashed File Name is not a valid  md5 hash.");
    }

    public function createDirectory($directoryPath)
    {
        if (!file_exists($directoryPath) && !is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0777, true)) {
                throw new DiretoryNotCreatedException("Unable to create directory.");
            }//end if
        }//end if
    }

    public function isValidMd5Name($fileName)
    {
        return strlen($fileName) == 32 && ctype_xdigit($fileName);
    }
}