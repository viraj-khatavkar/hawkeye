<?php namespace Viraj\Hawkeye;

use Illuminate\Support\Facades\Config;
use Viraj\Hawkeye\Exceptions\FileNotUploadedException;
use Viraj\Hawkeye\Exceptions\InvalidUploadedFileException;

class UploadedFile extends \SplFileInfo
{
    use HawkeyeTrait;

    /**
     * @var FileRepository
     */
    private $fileRepo;


    public function __construct($fileName, FileRepository $fileRepository)
    {
        parent::__construct($_FILES[$fileName]["tmp_name"]);

        $this->originalFile = $fileName;

        $this->originalFileObject = $this->original();

        $this->fileRepo = $fileRepository;
    }

    /**
     * Returns hashed file name of uploaded file.
     *
     * The function checks whether the file is a correct uploaded file via HTTP POST method and
     * if the file is not uploaded via HTTP POST, it throws InvalidUploadedFileException and
     * if file is uploaded via HTTP POST, it uploads file and returns hashed file name.
     *
     */
    public function upload()
    {
        if ($this->isUploadedFile($this)) {
            $fileData = [
                'name' => $this->originalFileObject->getFilename(),
                'extension' => $this->originalFileObject->getExtension(),
                'size' => $_FILES[$this->originalFile]["size"],
                'ip' => $_SERVER['REMOTE_ADDR'],
                'uploaded_at' => date('Y-m-d H:i:s')
            ];

            $this->createDirectory(
                $this->generateDirectoryPathFromName(
                    $this->fileRepo->storeFileAndGetName($fileData)
                )
            );
            $this->move($this->directoryPath, $this->hashedFileName);
            return $this->fileRepo->fileName.".".$this->originalFileObject->getExtension();
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
        $fullPathForFile = $path.'/'.$fileName.'.'.$this->original()->getExtension();

        if (!move_uploaded_file($this, $fullPathForFile)) {
            throw new FileNotUploadedException("Unable to move uploaded file to destination folder.");
        }
    }

    /**
     * Returns a high-level object oriented interface to information for original file.
     *
     * @return \SplFileInfo
     */
    public function original()
    {
        return new \SplFileInfo($_FILES[$this->originalFile]["name"]);
    }
}