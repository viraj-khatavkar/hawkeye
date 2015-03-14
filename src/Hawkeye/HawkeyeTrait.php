<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\Exceptions\DiretoryNotCreatedException;

trait HawkeyeTrait
{
    public function generateDirectoryPathFroName($hashedFileName)
    {
        return implode('/', str_split($hashedFileName, 3));
    }

    public function createDirectory($directoryPath)
    {
        if (!file_exists($directoryPath) && !is_dir($directoryPath)) {
            if (!mkdir($directoryPath, 0777, true)) {
                throw new DiretoryNotCreatedException("Unable to create directory.");
            }//end if
        }//end if
    }

}