<?php namespace Viraj\Hawkeye;

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

    public function make($filename)
    {
        if ($filename != '' && file_exists($filename)) {
            return new File($filename);
        }

        throw new InvalidFileException("File is corrupted or Invalid");
    }
}
