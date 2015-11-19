<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\Exceptions\DiretoryNotCreatedException;
use Viraj\Hawkeye\Exceptions\InvalidMd5HashException;

trait HawkeyeTrait
{
    public function generateFullImagePath($image_name)
    {
        $file = explode('.', $image_name);
        $file_meta = explode('_', $file[0]);

        $directoryPath = $this->generateDirectoryPathFromName($file_meta[0]);

        return $directoryPath . '/' . $image_name;
    }

    public function generateDirectoryPathFromName($hashedFileName)
    {
        if ($this->isValidMd5Name($hashedFileName)) {
            return config('hawkeye.hawkeye_base_path') . implode('/', str_split(substr($hashedFileName, 0, 30), 3));
        }
        throw new InvalidMd5HashException("The hashed File Name is not a valid  md5 hash.");
    }

    public function isValidMd5Name($fileName)
    {
        return strlen($fileName) == 32 && ctype_xdigit($fileName);
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