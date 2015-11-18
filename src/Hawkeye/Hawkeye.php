<?php namespace Viraj\Hawkeye;

use Viraj\Hawkeye\Exceptions\InvalidFileException;

class Hawkeye
{
    use HawkeyeTrait;

    private $files;
    private $fileObjects;

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

    public function upload($filename)
    {
        $this->normalize($_FILES[$filename])
            ->createFileObjects();

        foreach ($this->fileObjects as $fileObject) {
            $upload = new Upload($fileObject['uploaded'], $fileObject['original'], new FileRepository());

            $files[] = $upload->upload();
        }
    }

    private function normalize($argument)
    {
        if (is_array($argument['name'])) {
            foreach ($argument as $file_parameter => $value_array):
                foreach ($value_array as $key => $value):
                    $this->files[$key][$file_parameter] = $value;
                endforeach;
            endforeach;
        } else {
            foreach ($argument as $key => $value):
                $this->files[0][$key] = $value;
            endforeach;
        }

        return $this;
    }

    private function createFileObjects()
    {
        foreach ($this->files as $file):
            $this->fileObjects[] = [
                'original' => new \SplFileInfo($file['name']),
                'uploaded' => new \SplFileInfo($file['tmp_name']),
            ];
        endforeach;

        return $this;
    }
}
