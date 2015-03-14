<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\Exceptions\FileNotUploadedException;
use Viraj\Hawkeye\Exceptions\InvalidUploadedFileException;

class UploadedFile extends \SplFileInfo
{
    use HawkeyeTrait;

    private $fileRepo;

    private $fileName;

    private $directoryPath;

    public function __construct($fileName, FileRepository $fileRepository)
    {
        parent::__construct($_FILES[$fileName]["name"]);

        $this->temporaryFile = $_FILES[$fileName]["tmp_name"];

        $this->fileRepo = $fileRepository;
    }

    public function upload()
    {
        if ($this->isUploadedFile($this->temporaryFile)) {
            $this->fileName = $this->fileRepo->storeFileAndGetName();
            $this->directoryPath = $this->generateDirectoryPathFroName($this->fileName);
            $this->createDirectory($this->directoryPath);
            $this->move($this->directoryPath, $this->fileName);
        } else {
            throw new InvalidUploadedFileException("Invalid Upload request. File not uploaded via HTTP POST method");
        }
    }

    public function isUploadedFile(UploadedFile $filename)
    {
        return is_uploaded_file($filename);
    }

    public function move($path, $fileName)
    {
        $fullPathForFile = $path.'/'.$fileName.'.'.$this->getExtension();

        if (!move_uploaded_file($this->temporaryFile, $fullPathForFile)) {
            throw new FileNotUploadedException("Unable to move uploaded file to destination folder.");
        }
    }
}