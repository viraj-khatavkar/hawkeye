<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\Exceptions\FileNotUploadedException;
use Viraj\Hawkeye\Exceptions\InvalidUploadedFileException;

class UploadedFile extends \SplFileInfo
{
    use HawkeyeTrait;

    private $fileRepo;

    private $hashedFileName;

    private $directoryPath;

    public function __construct($fileName, FileRepository $fileRepository)
    {
        parent::__construct($_FILES[$fileName]["tmp_name"]);

        $this->originalFile = $fileName;

        $this->fileRepo = $fileRepository;
    }

    public function upload()
    {
        if ($this->isUploadedFile($this)) {
            $this->hashedFileName = $this->fileRepo->storeFileAndGetName();
            $this->directoryPath = $this->generateDirectoryPathFromName($this->hashedFileName);
            $this->createDirectory($this->directoryPath);
            $this->move($this->directoryPath, $this->hashedFileName);
            return true;
        } else {
            throw new InvalidUploadedFileException("Invalid Upload request. File not uploaded via HTTP POST method");
        }
    }

    private function isUploadedFile($filename)
    {
        return is_uploaded_file($filename);
    }

    private function move($path, $fileName)
    {
        $fullPathForFile = $path.'/'.$fileName.'.'.$this->getExtension();

        if (!move_uploaded_file($this->temporaryFile, $fullPathForFile)) {
            throw new FileNotUploadedException("Unable to move uploaded file to destination folder.");
        }
    }

    public function original()
    {
        return new \SplFileInfo($_FILES[$this->originalFile]["name"]);
    }
}