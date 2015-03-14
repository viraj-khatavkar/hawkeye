<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\Exceptions\InvalidUploadedFileException;

class UploadedFile extends \SplFileInfo
{

    public function upload()
    {
        if ($this->isUploadedFile($this)) {
            echo "yes...!!!";
        }
        throw new InvalidUploadedFileException;
    }

    public function isUploadedFile(File $filename)
    {
        return is_uploaded_file($filename);
    }
}