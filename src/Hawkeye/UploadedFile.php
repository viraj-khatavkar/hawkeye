<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\Exceptions\InvalidUploadedFileException;

class UploadedFile extends \SplFileInfo
{

    public function upload()
    {
        if ($this->isUploadedFile($this)) {
            echo "yes...!!!";
        }
        throw new InvalidUploadedFileException("Invalid File");
    }

    public function isUploadedFile(UploadedFile $filename)
    {
        return is_uploaded_file($filename);
    }
}