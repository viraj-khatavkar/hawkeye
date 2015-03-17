<?php namespace Viraj\Hawkeye;

use Illuminate\Support\Facades\Config;
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

        $this->originalFileObject = $this->original();

        $this->fileRepo = $fileRepository;
    }

    public function upload()
    {
        if ($this->isUploadedFile($this)) {
            $fileData = [
                'name' => $this->originalFileObject->getFilename(),
                'extension' => $this->originalFileObject->getExtension(),
                'size' => $this->originalFileObject->getSize(),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'uploaded_at' => date('Y-m-d H:i:s')
            ];

            $this->hashedFileName = $this->fileRepo->storeFileAndGetName($fileData);
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
        $fullPathForFile = $path.'/'.$fileName.'.'.$this->original()->getExtension();

        if (!move_uploaded_file($this, $fullPathForFile)) {
            throw new FileNotUploadedException("Unable to move uploaded file to destination folder.");
        }
    }

    public function original()
    {
        return new \SplFileInfo($_FILES[$this->originalFile]["name"]);
    }
}