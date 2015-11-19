<?php
namespace Viraj\Hawkeye;

use SplFileInfo;
use Viraj\Hawkeye\Exceptions\FileNotUploadedException;
use Viraj\Hawkeye\Exceptions\InvalidUploadedFileException;

class Upload
{
    use HawkeyeTrait;

    private $uploadedFile;
    private $originalFile;
    /**
     * @var FileRepository
     */
    private $fileRepository;

    public function __construct(SplFileInfo $uploadedFile, SplFileInfo $originalFile, FileRepository $fileRepository)
    {
        $this->uploadedFile = $uploadedFile;
        $this->originalFile = $originalFile;
        $this->fileRepository = $fileRepository;

        $this->directoryPath = config('hawkeye.hawkeye_base_path');
    }

    public function upload()
    {
        if ($this->isUploadedFile($this->uploadedFile)) {

            $fileData = [
                'name'        => $this->originalFile->getFilename(),
                'extension'   => $this->originalFile->getExtension(),
                'size'        => $this->uploadedFile->getSize(),
                'ip'          => $_SERVER['REMOTE_ADDR'],
                'uploaded_at' => date('Y-m-d H:i:s'),
            ];

            $this->hashedFileName = $this->fileRepository->storeFileAndGetName($fileData);
            $this->directoryPath = $this->generateDirectoryPathFromName($this->hashedFileName);
            $this->createDirectory($this->directoryPath);
            $this->move($this->directoryPath, $this->hashedFileName);

            return $this->hashedFileName . "." . $this->originalFile->getExtension();
        } else {
            throw new InvalidUploadedFileException("Invalid Upload request. File not uploaded via HTTP POST method");
        }
    }

    /**
     * Returns whether the file was uploaded via HTTP POST
     *
     * @param $filename
     * @return bool
     */
    private function isUploadedFile($filename)
    {
        return is_uploaded_file($filename);
    }

    /**
     * Moves the uploaded file to a desired location
     *
     * @param $path
     * @param $fileName
     * @throws FileNotUploadedException
     */
    private function move($path, $fileName)
    {
        $fullPathForFile = $path . '/' . $fileName . '.' . $this->originalFile->getExtension();

        if (!move_uploaded_file($this->uploadedFile, $fullPathForFile)) {
            throw new FileNotUploadedException("Unable to move uploaded file to destination folder.");
        }
    }
}